<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReservationNotification extends Notification
{
    use Queueable;

    public $reservation;
    public $title;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $message, $reservation)
    {
        $this->reservation = $reservation;
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

            'reservation_id'=> $this->reservation->id,
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
            'reservation_id'=> $this->reservation->id,
            'title' => $this->title,
            'message' => $this->message,
        ];
    }
}
