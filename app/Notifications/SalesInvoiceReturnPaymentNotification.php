<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SalesInvoiceReturnPaymentNotification extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $salesInvoiceReturn;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $message, $salesInvoiceReturn)
    {
        $this->title = $title;
        $this->message = $message;
        $this->salesInvoiceReturn = $salesInvoiceReturn;
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

            'sales_invoice_return'=> $this->salesInvoiceReturn->id,
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
            'sales_invoice_return'=> $this->salesInvoiceReturn->id,
            'title' =>  $this->title,
            'message' => $this->message,
        ];
    }
}
