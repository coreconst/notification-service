<?php

namespace App\Notifications\Channel\TypeMatchers;

use App\Notifications\Channel\Contracts\Type\ChannelTypeMatcherInterface;
use App\Notifications\Enum\NotificationChannelType;

class EmailMatcher implements ChannelTypeMatcherInterface
{
    public function matches(string $value): bool {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function getType(): NotificationChannelType
    {
        return NotificationChannelType::EMAIL;
    }
}
