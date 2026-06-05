<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class WelcomeVerifyEmail extends VerifyEmail
{
    /**
     * Genera la URL firmada de verificación (1 hora).
     */
    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id'   => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Renderiza correo con nuestra vista Blade.
     */
    public function toMail($notifiable): MailMessage
    {
        $url = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Confirma tu correo y ¡bienvenido a PROCAFES!')
            ->view('emails.verify-welcome', [
                'user' => $notifiable,
                'url'  => $url,
            ]);
    }
}
