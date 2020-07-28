<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class contact_send extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $phone;
    public $type;
    public $email;
    public $comment;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $phone, $type, $email, $comment)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->type = $type;
        $this->email = $email;
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Заявка с SamoCRM')->markdown('mail.contact');
//        return $this->view('view.name');
    }
}
