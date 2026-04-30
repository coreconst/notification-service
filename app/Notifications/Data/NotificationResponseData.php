<?php

namespace App\Notifications\Data;

use App\Notifications\Enum\NotificationChannelType;

readonly class NotificationResponseData
{
    public function __construct(
        public NotificationChannelType $channel,
        public string $message,
        public array $meta = []
    ){}

    public function toArray(): array
    {
        return [
            'channel' => $this->channel->value,
            'message' => $this->message,
            'meta' => $this->meta
        ];
    }
}
