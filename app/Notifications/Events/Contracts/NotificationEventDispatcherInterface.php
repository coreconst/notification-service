<?php

namespace App\Notifications\Events\Contracts;

use App\Notifications\Data\NotificationResponseData;
use App\Notifications\Enum\NotificationChannelType;

interface NotificationEventDispatcherInterface
{
    public function dispatchSent(string $recipient, string $message, NotificationChannelType $channel): void;

    public function dispatchFailed(
        string $recipient,
        string $message,
        NotificationChannelType $channel,
        string $error
    ): void;

    public function register(NotificationListenerInterface $listener): void;
}
