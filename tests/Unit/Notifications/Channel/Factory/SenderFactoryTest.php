<?php

namespace Tests\Unit\Notifications\Channel\Factory;

use App\Notifications\Channel\Exceptions\NotificationChannelException;
use App\Notifications\Channel\Factories\SenderFactory;
use App\Notifications\Channel\Senders\EmailSender;
use App\Notifications\Channel\Senders\PhoneSender;
use App\Notifications\Enum\NotificationChannelType;
use PHPUnit\Framework\TestCase;

class SenderFactoryTest extends TestCase
{
    private SenderFactory $factory;
    private EmailSender $emailSender;
    private PhoneSender $phoneSender;

    protected function setUp(): void
    {
        parent::setUp();

        $this->emailSender = $this->createMock(EmailSender::class);
        $this->phoneSender = $this->createMock(PhoneSender::class);

        $this->factory = new SenderFactory($this->phoneSender, $this->emailSender);
    }

    public function test_it_creates_email_sender(): void
    {
        $sender = $this->factory->make(NotificationChannelType::EMAIL);

        $this->assertSame($this->emailSender, $sender);
    }

    public function test_it_creates_phone_sender(): void
    {
        $sender = $this->factory->make(NotificationChannelType::PHONE);

        $this->assertSame($this->phoneSender, $sender);
    }

    public function test_it_throws_exception_for_unknown_type(): void
    {
        $this->expectException(NotificationChannelException::class);

        $this->factory->make(NotificationChannelType::UNKNOWN);
    }
}
