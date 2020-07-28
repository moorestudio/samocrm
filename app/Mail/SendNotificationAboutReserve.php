<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNotificationAboutReserve extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($event,$user)
    {
      $this->event = $event;
      $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Бронь билета')->markdown('mail.send_notification_about_reserve');
    }
}
