<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class MessageSent extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('New Message Sent')
            ->greeting('Hello ' . $notifiable->name)
            ->line('You have received a new message:')
            ->line($this->message->content)
            ->action('View Message', url('/messages/' . $this->message->id))
            ->line('Thank you for using our application!');
    }
}

