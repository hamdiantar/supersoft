<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
class RegisterCustomersNotifications extends Notification
{
    use Queueable;

    public $customerRequest;
    public $title;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $message, $customerRequest)
    {
        $this->customerRequest = $customerRequest;
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([

            'customer_request_id'=> $this->customerRequest->id,
            'title' => $this->title,
            'message' => $this->message,

        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'customer_request_id'=> $this->customerRequest->id,
            'title' => $this->title,
            'message' => $this->message,
        ];
    }
}
