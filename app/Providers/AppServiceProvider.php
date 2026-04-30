<?php

namespace App\Providers;

use App\Notifications\Contracts\NotificationGatewayInterface;
use App\Notifications\NotificationGateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ChannelTypeResolver::class, function ($app){
            return new ChannelTypeResolver([
                new EmailMatch(),
                new PhoneMatch(),
            ]);
        });

        $this->app->bind(NotificationGatewayInterface::class, NotificationGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
