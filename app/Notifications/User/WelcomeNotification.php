<?php

namespace App\Notifications\User;

use App\Enums\NamedQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param mixed $resetLink
     * @return void
     */
    public function __construct(public ?string $resetLink = null)
    {
        $this->onQueue(NamedQueue::MAILERS);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $appName = config('app.name');
        $mailMessage = (new MailMessage())
            ->subject("Welcome to {$appName}")
            ->greeting("Dear {$notifiable->full_name},")
            ->line('We welcome you to our application');

        if ($this->resetLink) {
            $mailMessage = $mailMessage->action('Set your password', $this->resetLink);
        }

        $mailMessage = $mailMessage->line('We hope you enjoy using our application!');

        return $mailMessage;
    }
}
