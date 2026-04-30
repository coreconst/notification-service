<?php

namespace Tests\Unit\Notifications\Channel\TypeMatchers;

use App\Notifications\Channel\TypeMatchers\PhoneMatcher;
use App\Notifications\Enum\NotificationChannelType;
use PHPUnit\Framework\TestCase;

class PhoneMatcherTest extends TestCase
{
    private PhoneMatcher $matcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->matcher = new PhoneMatcher();
    }

    public function test_it_matches_valid_phone_numbers(): void
    {
        $this->assertTrue($this->matcher->matches('+380123456789'));
        $this->assertTrue($this->matcher->matches('1234567890'));
        $this->assertTrue($this->matcher->matches('+1234567'));
        $this->assertTrue($this->matcher->matches('123456789012345'));
    }

    public function test_it_does_not_match_invalid_phone_numbers(): void
    {
        $this->assertFalse($this->matcher->matches('123456'));
        $this->assertFalse($this->matcher->matches('1234567890123456'));
        $this->assertFalse($this->matcher->matches('+123 456 7890'));
        $this->assertFalse($this->matcher->matches('abc1234567'));
    }

    public function test_it_does_not_match_email(): void
    {
        $this->assertFalse($this->matcher->matches('test@example.com'));
    }

    public function test_it_returns_phone_type(): void
    {
        $this->assertEquals(NotificationChannelType::PHONE, $this->matcher->getType());
    }
}
