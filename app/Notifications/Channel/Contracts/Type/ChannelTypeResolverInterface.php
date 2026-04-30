<?php

namespace App\Notifications\Channel\Contracts\Type;

use App\Notifications\Enum\NotificationChannelType;

interface ChannelTypeResolverInterface
{
    public function resolve(string $to): NotificationChannelType;
}
