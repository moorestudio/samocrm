<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCertificates extends Mailable
{
    use Queueable, SerializesModels;
    public $ticket;
    public $user;
    public $event;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ticket,$user,$event)
    {
      $this->ticket = $ticket;
      $this->user = $user;
      $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->subject('Сертификат')->markdown('mail.send_certificate');
    }
}
