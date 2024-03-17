<?php

namespace App\Notifications\Products;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Approval extends Notification
{
    use Queueable;
    /**
     * Create a new notification instance.
     */
    public function __construct(protected $message)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line($this->message)
                    //->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }
}
