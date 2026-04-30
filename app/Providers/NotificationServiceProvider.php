<?php

namespace App\Providers;

use App\Notifications\Channel\Contracts\Type\ChannelTypeResolverInterface;
use App\Notifications\Channel\Resolvers\ChannelTypeResolver;
use App\Notifications\Channel\TypeMatchers\EmailMatcher;
use App\Notifications\Channel\TypeMatchers\PhoneMatcher;
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
        $this->app->singleton(ChannelTypeResolverInterface::class, function ($app){
            return new ChannelTypeResolver([
                new EmailMatcher(),
                new PhoneMatcher(),
            ]);
        });

        $this->app->bind(NotificationGatewayInterface::class, function ($app){
            return new NotificationGateway(
                $app->make(ChannelTypeResolverInterface::class)
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
