<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Los listeners de eventos de tu aplicación.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Registra cualquier servicio de eventos/autorización.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Si Laravel debe descubrir eventos automáticamente.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
