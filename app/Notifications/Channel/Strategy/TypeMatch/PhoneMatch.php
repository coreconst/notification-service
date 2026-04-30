<?php

namespace App\Notifications\Channel\Strategy\TypeMatch;

use App\Notifications\Channel\Contracts\Type\ChannelTypeMatchInterface;
use App\Notifications\Enum\NotificationChannelType;

class PhoneMatch implements ChannelTypeMatchInterface
{
    public function matches(string $value): bool {
        return preg_match('/^\+?[0-9]{7,15}$/', $value);
    }

    public function getType(): NotificationChannelType
    {
        return NotificationChannelType::SMS;
    }
}
