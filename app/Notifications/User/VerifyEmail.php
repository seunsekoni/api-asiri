<?php

namespace App\Notifications\User;

use App\Enums\NamedQueue;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param string $callbackUrl
     * @return void
     */
    public function __construct(public string $callbackUrl)
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
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage())
            ->subject('Verify Email Address')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email Address', $verificationUrl)
            ->line('If you did not create an account, no further action is required.');
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $signedRoute = URL::temporarySignedRoute(
            'user.verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        // Pull down the signed route for restructuring with the callbackUrl
        $parsedUrl = parse_url($signedRoute);
        parse_str($parsedUrl['query'], $urlQueries);

        // Build the query parameters
        $parameters = http_build_query([
            'expires' => $urlQueries['expires'],
            'hash' => $urlQueries['hash'],
            'id' => $urlQueries['id'],
            'signature' => $urlQueries['signature']
        ]);

        return "{$this->callbackUrl}?{$parameters}";
    }
}
