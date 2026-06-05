<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class SendWelcomeAfterVerification
{
    public function handle(Verified $event): void
    {
        $user = $event->user;
        // envía el correo de bienvenida
        Mail::to($user->email)->send(new WelcomeMail($user));
    }
}
