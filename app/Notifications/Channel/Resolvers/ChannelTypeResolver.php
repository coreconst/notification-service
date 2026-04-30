<?php

namespace App\Notifications\Channel\Resolvers;

use App\Notifications\Channel\Contracts\Type\ChannelTypeMatchInterface;
use App\Notifications\Channel\Contracts\Type\ChannelTypeResolverInterface;
use App\Notifications\Enum\NotificationChannelType;

class ChannelTypeResolver implements ChannelTypeResolverInterface
{
    public function __construct(
        /** @var array<ChannelTypeMatchInterface> */
        private array $matchers
    ){}

    public function resolve(string $to): NotificationChannelType
    {
        $to = trim($to);

        foreach ($this->matchers as $matcher) {
            if ($matcher->matches($to)) {
                return $matcher->getType();
            }
        }

        return NotificationChannelType::UNKNOWN;
    }
}
