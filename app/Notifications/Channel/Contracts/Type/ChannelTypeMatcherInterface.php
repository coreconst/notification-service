<?php

namespace App\Notifications\Channel\Contracts\Type;

use App\Notifications\Enum\NotificationChannelType;

interface ChannelTypeMatcherInterface
{
    public function matches(string $value): bool;
    public function getType(): NotificationChannelType;
}
