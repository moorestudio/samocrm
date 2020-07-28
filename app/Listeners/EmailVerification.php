<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMailAble;
use App\Http\Controllers\AMOController;

class EmailVerification
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
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)

    {   
        $user_email = $event->user->email;
        $user = $event->user;
        $pass = $event->pass;
        
        Mail::to($user_email)->send(new EmailVerificationMailAble($user,$pass,$user_email));
        
    }
}
