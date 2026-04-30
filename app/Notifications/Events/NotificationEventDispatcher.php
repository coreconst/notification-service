<?php

namespace App\Notifications\Events;

use App\Notifications\Enum\NotificationChannelType;
use App\Notifications\Events\Contracts\NotificationEventDispatcherInterface;
use App\Notifications\Events\Contracts\NotificationListenerInterface;
use App\Notifications\Events\Data\NotificationFailed;
use App\Notifications\Events\Data\NotificationSent;

class NotificationEventDispatcher implements NotificationEventDispatcherInterface
{
    /**
     * @param NotificationListenerInterface[] $listeners
     */
    public function __construct(
        private array $listeners = []
    ) {}

    public function register(NotificationListenerInterface $listener): void
    {
        $this->listeners[] = $listener;
    }

    public function dispatchSent(
        string $recipient,
        string $message,
        NotificationChannelType $channel
    ): void {
        $event = new NotificationSent(
            recipient: $recipient,
            message: $message,
            channel: $channel,
            sentAt: new \DateTimeImmutable()
        );

        foreach ($this->listeners as $listener) {
            $listener->handleSent($event);
        }
    }

    public function dispatchFailed(
        string $recipient,
        string $message,
        NotificationChannelType $channel,
        string $error
    ): void {
        $event = new NotificationFailed(
            recipient: $recipient,
            message: $message,
            channel: $channel,
            error: $error,
            failedAt: new \DateTimeImmutable()
        );

        foreach ($this->listeners as $listener) {
            $listener->handleFailed($event);
        }
    }
}
