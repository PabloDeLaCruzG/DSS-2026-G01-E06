<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), 'GameLink No Reply')
            ->subject('Bienvenido a GameLink')
            ->view('emails.auth.welcome', [
                'user' => $notifiable,
                'homeUrl' => url('/'),
                'supportEmail' => config('mail.from.address'),
            ]);
    }
}
