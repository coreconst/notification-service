<?php

namespace App\Notifications\Channel\Strategy\TypeMatch;

use App\Notifications\Channel\Contracts\Type\ChannelTypeMatchInterface;
use App\Notifications\Enum\NotificationChannelType;

class EmailMatch implements ChannelTypeMatchInterface
{
    public function matches(string $value): bool {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function getType(): NotificationChannelType
    {
        return NotificationChannelType::EMAIL;
    }
}
