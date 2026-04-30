<?php

namespace App\Providers\Notification;

use App\Notifications\Channel\Contracts\SenderFactoryInterface;
use App\Notifications\Channel\Contracts\Type\ChannelTypeResolverInterface;
use App\Notifications\Channel\Factories\SenderFactory;
use App\Notifications\Channel\Resolvers\ChannelTypeResolver;
use App\Notifications\Channel\Senders\EmailSender;
use App\Notifications\Channel\Senders\PhoneSender;
use App\Notifications\Channel\TypeMatchers\EmailMatcher;
use App\Notifications\Channel\TypeMatchers\PhoneMatcher;
use Illuminate\Support\ServiceProvider;

class ChannelServiceProvider extends ServiceProvider
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

        $this->app->singleton(SenderFactoryInterface::class, function ($app){
            $url = config('services.betterme.url');
            return new SenderFactory(
                new PhoneSender($url),
                new EmailSender($url),
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
