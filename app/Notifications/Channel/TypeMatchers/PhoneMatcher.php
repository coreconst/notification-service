<?php

namespace App\Notifications\Channel\TypeMatchers;

use App\Notifications\Channel\Contracts\Type\ChannelTypeMatcherInterface;
use App\Notifications\Enum\NotificationChannelType;

class PhoneMatcher implements ChannelTypeMatcherInterface
{
    public function matches(string $value): bool {
        return preg_match('/^\+?[0-9]{7,15}$/', $value);
    }

    public function getType(): NotificationChannelType
    {
        return NotificationChannelType::SMS;
    }
}
