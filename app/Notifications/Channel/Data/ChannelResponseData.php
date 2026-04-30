<?php

namespace App\Notifications\Channel\Data;

use App\Notifications\Channel\Enum\ChannelResponseStatus;

readonly class ChannelResponseData
{
    public function __construct(
        public ChannelResponseStatus $status,
        public ?string $message = '',
        public array $meta = []
    ){}

    public static function success(?string $message = null, array $meta = []): static
    {
        return new ChannelResponseData(
            ChannelResponseStatus::SUCCESS,
            $message,
            $meta
        );
    }

    public static function error(?string $message = null, array $meta = []): static
    {
        return new ChannelResponseData(
            ChannelResponseStatus::ERROR,
            $message,
            $meta
        );
    }
}
