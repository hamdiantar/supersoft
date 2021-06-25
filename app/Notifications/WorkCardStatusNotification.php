<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WorkCardStatusNotification extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $workCard;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $message, $workCard)
    {
        $this->title = $title;
        $this->message = $message;
        $this->workCard = $workCard;
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

            'work_card_id'=> $this->workCard->id,
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
            'work_card_id'=> $this->workCard->id,
            'title' =>  $this->title,
            'message' => $this->message,
        ];
    }
}
