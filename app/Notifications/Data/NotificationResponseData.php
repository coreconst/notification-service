<?php

namespace App\Notifications\Data;

use App\Notifications\Channel\Enum\ChannelResponseStatus;
use App\Notifications\Enum\NotificationChannelType;

readonly class NotificationResponseData
{
    public function __construct(
        public NotificationChannelType $channel,
        public ChannelResponseStatus $status,
        public string $sentMessage,
        public array $meta = []
    ){}

    public function toArray(): array
    {
        return [
            'channel' => $this->channel->value,
            'status' => $this->status->value,
            'message' => $this->sentMessage,
            'meta' => $this->meta
        ];
    }
}
