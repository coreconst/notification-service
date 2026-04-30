<?php

namespace Tests\Unit\Notifications\Channel\TypeMatchers;

use App\Notifications\Channel\TypeMatchers\EmailMatcher;
use App\Notifications\Enum\NotificationChannelType;
use PHPUnit\Framework\TestCase;

class EmailMatcherTest extends TestCase
{
    private EmailMatcher $matcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->matcher = new EmailMatcher();
    }

    public function test_it_matches_valid_email(): void
    {
        $this->assertTrue($this->matcher->matches('test@example.com'));
        $this->assertTrue($this->matcher->matches('user.name+tag@example.co.uk'));
        $this->assertTrue($this->matcher->matches('test123@test-domain.com'));
    }

    public function test_it_does_not_match_invalid_email(): void
    {
        $this->assertFalse($this->matcher->matches('invalid-email'));
        $this->assertFalse($this->matcher->matches('test@'));
        $this->assertFalse($this->matcher->matches('@example.com'));
        $this->assertFalse($this->matcher->matches('test @example.com'));
    }

    public function test_it_does_not_match_phone_number(): void
    {
        $this->assertFalse($this->matcher->matches('+380123456789'));
        $this->assertFalse($this->matcher->matches('1234567890'));
    }

    public function test_it_returns_email_type(): void
    {
        $this->assertEquals(NotificationChannelType::EMAIL, $this->matcher->getType());
    }
}
