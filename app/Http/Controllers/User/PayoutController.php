<?php

namespace App\Http\Controllers\User;

use App\Mail\OtpMail;
use App\Models\Payout;
use App\Mail\PayoutMail;
use App\Models\PayoutMethod;
use Illuminate\Http\Request;
use App\Models\UserPayoutMethod;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\PageHeader;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Session;
use Auth;

class PayoutController extends Controller
{
    public function index()
    {
        PageHeader::set()->title('Payout methods')
            ->buttons([
                ['name' => 'Payout History', 'url' => route('user.payout.logs')]
            ]);

        $pending_amount = Payout::where('user_id', Auth::id())->whereStatus('pending')->sum('amount');
        $approved_amount = Payout::where('user_id', Auth::id())->whereStatus('approved')->sum('amount');

        $methods = PayoutMethod::whereStatus(1)
            ->with('usermethod', function ($q) {
                return $q->where('user_id', auth()->id());
            })->latest()->get();
            
        return Inertia::render('User/Payout/Index', [
            'methods' => $methods,
            'pending_amount' => amount_format($pending_amount, 'icon'),
            'approved_amount' => amount_format($approved_amount, 'icon'),
        ]);
    }


    public function show($method_id)
    {
        PageHeader::set()->title('Payout History')
        ->buttons([
            ['name' => 'Back', 'url' => route('user.payout.index')]
        ]);

        $method = PayoutMethod::whereStatus(1)
            ->with('usermethod', function ($q) {
                return $q->where('user_id', auth()->id());
            })->whereHas('usermethod', function ($q) {
                return $q->where('user_id', auth()->id());
            })->findOrFail($method_id);

        $userPayoutInfo = json_decode($method->usermethod->payout_infos ?? '');
        $wallet = Auth::user()->wallet;

        return Inertia::render('User/Payout/Show', [
            'method' => $method,
            'userPayoutInfo' => $userPayoutInfo,
            'wallet' => $wallet
        ]);
    }

    public function edit($method_id)
    {
        $method = PayoutMethod::whereStatus(1)
        ->with('usermethod', function ($q) {
            return $q->where('user_id', auth()->id());
        })->findOrFail($method_id);

        PageHeader::set()->title($method->name)
        ->buttons([
            ['name' => 'Back', 'url' => route('user.payout.index')]
        ]);
        

        $userPayoutInfo = $method->usermethod != null ? json_decode($method->usermethod->payout_infos ?? '') : [];
        $fields = json_decode($method->data);

        return Inertia::render('User/Payout/Edit', [
            'method' => $method,
            'fields' => $fields,
            'userPayoutInfo' => $userPayoutInfo,
        ]);
    }

    public function update(Request $request, $method_id)
    {

        $usermethod = UserPayoutMethod::where('payout_method_id', $method_id)->where('user_id', auth()->id())->first();
        if (!$usermethod) {
            $usermethod = new UserPayoutMethod;
            $usermethod->payout_method_id = $method_id;
            $usermethod->user_id = Auth::id();
        }
        $data = json_encode($request->inputs);
        $usermethod->payout_infos = $data;
        $usermethod->save();

        return back();
    }

    public function makepayout($method_id)
    {
        $usermethod = UserPayoutMethod::where('payout_method_id', $method_id)->where('user_id', auth()->id())->first();
        if (!$usermethod) {
            abort(404);
        }
        $method = PayoutMethod::whereStatus(1)->findOrFail($method_id);
        return view('user.payout.payout-request', compact('method', 'usermethod'));
    }

    public function sentOtp(Request $request, $method_id)
    {
        $request->validate([
            'amount' => 'required|integer',
        ]);

        $method = PayoutMethod::whereStatus(1)->with('usermethod', function ($q) {
            return $q->where('user_id', auth()->id());
        })->whereHas('usermethod', function ($q) {
            return $q->where('user_id', auth()->id());
        })->findOrFail($method_id);

        if ($method) {
            if (auth()->user()->wallet >= $request->amount) {
                if ($method->min_limit <= $request->amount) {
                    if ($method->max_limit >= $request->amount) {

                        $otp = rand();
                        $payoutMetaData = ['payout_otp' => $otp, 'payout_amount' => $request->amount, 'method_id' => $method->id];
                        session()->put('payoutdata', $payoutMetaData);


                        try {
                            if (env('QUEUE_MAIL')) {
                                Mail::to(auth()->user()->email)->queue(new OtpMail($otp));
                            } else {
                                Mail::to(auth()->user()->email)->send(new OtpMail($otp));
                            }
                        } catch (Exception $e) {
                            return response()->json('The Mail Settings Has Issues', 404);
                        }


                        return Inertia::location(route('user.payout.confirmation'));
                    } else {
                        return back()->withErrors([
                            'amount' => 'Maximum transaction amount ' . $method->max_limit,
                        ])->onlyInput('amount');
                    }
                } else {
                    return back()->withErrors([
                        'amount' => 'Minimum transaction amount ' . $method->min_limit,
                    ])->onlyInput('amount');
                }
            } else {

                return back()->withErrors([
                    'amount' => 'Insufficient wallet. Your wallet is ' . (auth()->user()->wallet ?? 0),
                ])->onlyInput('amount');
            }
        } else {
            return response()->json('Method not found.', 404);
        }
    }


    function confirmation()
    {
        abort_if(!Session::has('payoutdata'), 404);
        $segments = request()->segments();
        $buttons = [];
        $sessionData = Session::get('payoutdata');
        $method_id = $sessionData['method_id'];
        $payout_otp = $sessionData['payout_otp'];
        $payout_amount = $sessionData['payout_amount'];


        $method = PayoutMethod::whereStatus(1)->with('usermethod', function ($q) {
            return $q->where('user_id', auth()->id());
        })->whereHas('usermethod', function ($q) {
            return $q->where('user_id', auth()->id());
        })->findOrFail($method_id);


        $wallet = Auth::user()->wallet;
        $userPayoutInfo = json_decode($method->usermethod->payout_infos ?? '');

        return Inertia::render('User/Payout/Confirmation', compact('method', 'wallet', 'payout_amount', 'userPayoutInfo', 'segments', 'buttons'));
    }

    public function makeRequest(Request $request)
    {
        abort_if(!Session::get('payoutdata'), 404);

        $sessionData = Session::get('payoutdata');
        $method_id = $sessionData['method_id'];
        $payout_otp = $sessionData['payout_otp'];
        $payout_amount = $sessionData['payout_amount'];

        $request->validate([
            'otp' => 'required|integer',
        ]);

        if ($payout_otp != $request->otp) {
            return back()->withErrors([
                'otp' => 'OTP Doesnt Match',
            ])->onlyInput('otp');
        }


        $method = PayoutMethod::whereStatus(1)->with('usermethod', function ($q) {
            return $q->where('user_id', auth()->id());
        })->whereHas('usermethod', function ($q) {
            return $q->where('user_id', auth()->id());
        })->findOrFail($method_id);

        $wallet = Auth::user()->wallet;

        if ($payout_amount > $wallet) {
            return back()->withErrors([
                'otp' => 'Balance Low',
            ])->onlyInput('otp');
        }

        $userPayoutInfo = $method->usermethod->payout_infos ?? '';

        $charge = $method->charge_type == 'fixed' ? $method->fixed_charge : ($method->percent_charge / 100) * $payout_amount;
        $payout_after_charge = $payout_amount - $charge;

        DB::beginTransaction();
        try {

            $payout = Payout::create([
                'charge' => $charge,
                'user_id' => auth()->id(),
                'amount' => $payout_amount,
                'currency' => $method->currency_name,
                'payout_method_id' => $method_id,
                'meta' => $userPayoutInfo
            ]);
            auth()->user()->update([
                'wallet' => auth()->user()->wallet - $payout_amount
            ]);

            Mail::to(env('MAIL_TO'))->queue(new PayoutMail($payout));

            Session::forget('payoutdata');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
          
            return back()->withErrors([
                'otp' => __('Something Wrong Please Create Support Ticket'),
            ])->onlyInput('otp');
        }

        return Inertia::location(route('user.payout.logs'));
    }

    public function logs()
    {
        PageHeader::set()->title('Payout History')
            ->buttons([
                ['name' => 'Back', 'url' => route('user.payout.index')]
            ]);

        $payouts = Payout::where('user_id', Auth::id())->with('method')->latest()->paginate(30);

        $total_approved_requests =  Payout::where('user_id', Auth::id())->where('status', 'completed')->count();
        $total_pending_requests =  Payout::where('user_id', Auth::id())->where('status', 'pending')->count();
        $total_failed_requests =  Payout::where('user_id', Auth::id())->where('status', 'failed')->count();
        return Inertia::render(
            'User/Payout/Requests',
            compact(
                'payouts',
                'total_approved_requests',
                'total_pending_requests',
                'total_failed_requests',
             
            )
        );
    }

    public function invoice($id)
    {
        PageHeader::set()->title('Payout History')
        ->buttons([
            ['name' => 'Back', 'url' => route('user.payout.logs')]
        ]);

        $payout = Payout::where('user_id', Auth::id())->with('method')->where('invoice_no', $id)->first();
        abort_if(empty($payout), 404);
        $userPayoutInfo = json_decode($payout->meta ?? '');
        $method = $payout->method;

        return Inertia::render(
            'User/Payout/Invoice',
            compact('payout', 'userPayoutInfo', 'method')
        );
    }
}
