<?php

namespace Tests\Unit\Notifications\Channel\Resolvers;

use App\Notifications\Channel\Contracts\Type\ChannelTypeMatcherInterface;
use App\Notifications\Channel\Resolvers\ChannelTypeResolver;
use App\Notifications\Enum\NotificationChannelType;
use PHPUnit\Framework\TestCase;

class ChannelTypeResolverTest extends TestCase
{
    public function test_it_resolves_to_first_matching_type(): void
    {
        $emailMatcher = $this->createMock(ChannelTypeMatcherInterface::class);
        $emailMatcher->method('matches')->willReturn(true);
        $emailMatcher->method('getType')->willReturn(NotificationChannelType::EMAIL);

        $phoneMatcher = $this->createMock(ChannelTypeMatcherInterface::class);
        $phoneMatcher->method('matches')->willReturn(false);

        $resolver = new ChannelTypeResolver([$emailMatcher, $phoneMatcher]);

        $result = $resolver->resolve('test@example.com');

        $this->assertEquals(NotificationChannelType::EMAIL, $result);
    }

    public function test_it_returns_unknown_when_no_matcher_matches(): void
    {
        $emailMatcher = $this->createMock(ChannelTypeMatcherInterface::class);
        $emailMatcher->method('matches')->willReturn(false);

        $phoneMatcher = $this->createMock(ChannelTypeMatcherInterface::class);
        $phoneMatcher->method('matches')->willReturn(false);

        $resolver = new ChannelTypeResolver([$emailMatcher, $phoneMatcher]);

        $result = $resolver->resolve('invalid-value');

        $this->assertEquals(NotificationChannelType::UNKNOWN, $result);
    }
}
