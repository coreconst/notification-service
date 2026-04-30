<?php

namespace App\Notifications\Events\Data;

use App\Notifications\Enum\NotificationChannelType;

readonly class NotificationFailed
{
    public function __construct(
        public string $recipient,
        public string $message,
        public NotificationChannelType $channel,
        public string $error,
        public \DateTimeImmutable $failedAt
    ) {}
}
