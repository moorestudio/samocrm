<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FranchInvitation extends Mailable
{
    use Queueable, SerializesModels;
    protected $email;
    protected $user;
    protected $pass;
    protected $log_url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$pass,$log_url)
    {
        $this->email = $user->email;
        $this->user = $user;
        $this->pass = $pass;
        $this->log_url = $log_url;
        $this->subject('Пригласительное');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.franchInvitation', [
        'name' => $this->user->name,
        'last_name' => $this->user->last_name,
        'pass' => $this->pass,
        'log_url'=> $this->log_url,
        'email'=> $this->email
        ]);
    }
}
