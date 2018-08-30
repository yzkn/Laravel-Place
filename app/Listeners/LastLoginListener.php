<?php

namespace App\Listeners;

use App\Events\Logined;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

 // lastloginカラム用
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LastLoginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Logined  $event
     * @return void
     */
    public function handle(Logined $event)
    {
        $user = Auth::user();
        $user->lastlogin_at = Carbon::now();
        $user->save();
    }
}
