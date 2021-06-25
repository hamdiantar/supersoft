<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SalesInvoicePaymentsNotification extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $salesInvoice;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $message, $salesInvoice)
    {
        $this->title = $title;
        $this->message = $message;
        $this->salesInvoice = $salesInvoice;
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

            'sales_invoice'=> $this->salesInvoice->id,
            'title' =>  $this->title,
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
            'sales_invoice'=> $this->salesInvoice->id,
            'title' =>  $this->title,
            'message' => $this->message,
        ];
    }
}
