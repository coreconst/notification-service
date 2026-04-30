<?php

namespace App\Notifications\Channel\Contracts;

use App\Notifications\Channel\Data\ChannelResponseData;

interface SenderInterface
{
    public function send(string $recipient, string $text): ChannelResponseData;
}
