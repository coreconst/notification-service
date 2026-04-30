<?php

namespace App\Notifications\Channel\Contracts;

use App\Notifications\Enum\NotificationChannelType;

interface SenderFactoryInterface
{
    public function make(NotificationChannelType $type): SenderInterface;
}
