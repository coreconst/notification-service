<?php

namespace App\Notifications\Events\Data;

use App\Notifications\Enum\NotificationChannelType;

readonly class NotificationSent
{
    public function __construct(
        public string $recipient,
        public string $message,
        public NotificationChannelType $channel,
        public \DateTimeImmutable $sentAt
    ) {}
}
