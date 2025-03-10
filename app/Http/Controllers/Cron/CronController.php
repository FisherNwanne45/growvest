<?php

namespace App\Http\Controllers\Cron;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\Notifications;
use App\Http\Controllers\Controller;

class CronController extends Controller
{
    use Notifications;


    /**
     * notify to subscribers before expire the subscription
     *
     * @return \Illuminate\Http\Response
     */
    public function notifyToUser()
    {
        $willExpire = today()->addDays(7)->format('Y-m-d');
        $users = User::whereHas('subscription')->with('subscription')->where('will_expire', $willExpire)->latest()->get();

        foreach ($users as $key => $user) {
            $this->sentWillExpireEmail($user);
        }

        return "Cron job executed";
    }
}
