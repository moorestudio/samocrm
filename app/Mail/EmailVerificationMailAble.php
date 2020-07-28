<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerificationMailAble extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$pass)
    {
        $this->user = $user;
        $this->pass = $pass;
        $this->subject('Подтверждение почты');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->markdown('mail.verify-email');
        return $this->markdown('mail.verify-email', [
        'token' => $this->user->confirmation_token,
        'id' => $this->user->id,
        'pass' => $this->pass,
        'email' => $this->user->email
        ]);
    }
}
