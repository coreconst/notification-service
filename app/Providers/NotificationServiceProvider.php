<?php

namespace App\Providers;

use App\Notifications\Channel\Contracts\SenderFactoryInterface;
use App\Notifications\Channel\Contracts\Type\ChannelTypeResolverInterface;
use App\Notifications\Contracts\NotificationGatewayInterface;
use App\Notifications\NotificationGateway;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(NotificationGatewayInterface::class, function ($app){
            return new NotificationGateway(
                $app->make(ChannelTypeResolverInterface::class),
                $app->make(SenderFactoryInterface::class)
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
