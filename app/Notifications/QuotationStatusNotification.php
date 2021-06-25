<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class QuotationStatusNotification extends Notification
{
    use Queueable;

    public $quotationRequest;
    public $title;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $message, $quotationRequest)
    {
        $this->quotationRequest = $quotationRequest;
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

            'quotation_request_id'=> $this->quotationRequest->id,
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
            'quotation_request_id'=> $this->quotationRequest->id,
            'title' => $this->title,
            'message' => $this->message,
        ];
    }
}
