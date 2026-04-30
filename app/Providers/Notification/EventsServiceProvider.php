<?php

namespace App\Providers\Notification;

use App\Events\Registers\NotificationListenersRegister;
use App\Notifications\Events\Contracts\NotificationEventDispatcherInterface;
use App\Notifications\Events\NotificationEventDispatcher;
use Illuminate\Support\ServiceProvider;

class EventsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(NotificationEventDispatcherInterface::class, function ($app) {
            return new NotificationEventDispatcher(
                NotificationListenersRegister::listeners()
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
