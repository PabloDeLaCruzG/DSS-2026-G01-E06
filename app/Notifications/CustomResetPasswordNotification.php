<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $expireMinutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

        return (new MailMessage)
            ->from(config('mail.from.address'), 'GameLink No Reply')
            ->subject('Recuperación de contraseña - GameLink')
            ->view('emails.auth.reset-password', [
                'user' => $notifiable,
                'resetUrl' => $resetUrl,
                'expireMinutes' => $expireMinutes,
                'supportEmail' => config('mail.from.address'),
            ]);
    }
}
