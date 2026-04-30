<?php

namespace App\Notifications\Data;

readonly class NotificationSendData
{
    public function __construct(
        public string $to,
        public string $message,
        public array $meta = []
    ){}

    public static function fromArray(array $data = []): static
    {
        if(!isset($data['to'], $data['message'])){
            throw new \InvalidArgumentException('Invalid data provided');
        }

        return new static($data['to'], $data['message'], $data['meta'] ?? []);
    }
}
