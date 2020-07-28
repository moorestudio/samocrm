<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserChangeFranch extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $comment;
    public $phone;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $comment, $phone)
    {
        $this->user = $user;
        $this->comment = $comment;
        $this->phone = $phone;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Заявка от клиента о смене консультанта')->markdown('mail.send_user_change_franch');
    }
}
