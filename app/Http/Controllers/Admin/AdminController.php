<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Services\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin');
    }

    public function index()
    {
        $segments = request()->segments();
        $buttons = [
            [
                'name' => '<i class="fa fa-plus"></i>&nbsp' . __('Create a admin'),
                'url' => route('admin.admin.create')
            ]
        ];
        $users = User::where('role', 'admin')->with('roles')->where('id', '!=', 1)->latest()->get();

        return Inertia::render('Admin/Admin/Index', compact('users', 'segments', 'buttons'));
    }


    public function create()
    {

        $roles = Role::all();
        $segments = request()->segments();
        $buttons = [
            [
                'name' => __('Back'),
                'url' => route('admin.admin.index')
            ]
        ];

        return Inertia::render('Admin/Admin/Create', compact('roles', 'segments', 'buttons'));
    }

    public function store(Request $request)
    {

        // Validation Data
        $request->validate([
            'name' => 'required|max:50',
            'roles' => 'required',
            'email' => 'required|max:100|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // Create New User
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = 'admin';
        $user->password = Hash::make($request->password);
        $user->save();

        if ($request->roles) {
            $user->assignRole($request->roles);
        }

        Toastr::success(__('Admin created successfully'));

        return redirect()->route('admin.admin.index');
    }


    public function edit($id)
    {

        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();

        $segments = request()->segments();
        $buttons = [
            [
                'name' => __('Back'),
                'url' => route('admin.admin.index')
            ]
        ];

        return Inertia::render('Admin/Admin/Edit', compact('segments', 'buttons', 'user', 'roles'));
    }


    public function update(Request $request, $id)
    {
        // Create New User
        $user = User::find($id);

        // Validation Data
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|max:100|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
        ]);


        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = $request->status;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $user->roles()->detach();
        if ($request->roles) {
            $user->assignRole($request->roles);
        }

        Toastr::success(__('Admin updated successfully'));

        return back();
    }

    public function destroy($id)
    {

        User::destroy($id);
        Toastr::success(__('Admin deleted successfully'));
        return back();
    }


    public function adminNotifications()
    {
        return
            Notification::query()
                ->where('is_admin', 1)
                ->orderBy('seen', 'DESC')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    $item->title_short = Str::limit($item->title, 30, '...');
                    $item->comment_short = Str::limit($item->comment, 35, '...');
                    return $item;
                });
    }

    public function adminNotificationsRead(Notification $notification)
    {
        $notification->seen = 1;
        $notification->save();
        return response()->json([
            'success' => true
        ]);
    }
}
