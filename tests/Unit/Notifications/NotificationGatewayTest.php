<?php

namespace Tests\Unit\Notifications;

use App\Notifications\Channel\Contracts\SenderFactoryInterface;
use App\Notifications\Channel\Contracts\SenderInterface;
use App\Notifications\Channel\Contracts\Type\ChannelTypeResolverInterface;
use App\Notifications\Channel\Data\ChannelResponseData;
use App\Notifications\Channel\Enum\ChannelResponseStatus;
use App\Notifications\Channel\Exceptions\NotificationChannelException;
use App\Notifications\Data\NotificationResponseData;
use App\Notifications\Data\NotificationSendData;
use App\Notifications\Enum\NotificationChannelType;
use App\Notifications\Events\NotificationEventDispatcher;
use App\Notifications\NotificationGateway;
use PHPUnit\Framework\TestCase;

class NotificationGatewayTest extends TestCase
{
    private ChannelTypeResolverInterface $resolver;
    private SenderFactoryInterface $factory;
    private NotificationEventDispatcher $eventDispatcher;
    private NotificationGateway $gateway;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = $this->createMock(ChannelTypeResolverInterface::class);
        $this->factory = $this->createMock(SenderFactoryInterface::class);
        $this->eventDispatcher = $this->createMock(NotificationEventDispatcher::class);

        $this->gateway = new NotificationGateway(
            $this->resolver,
            $this->factory,
            $this->eventDispatcher
        );
    }

    public function test_it_sends_notification_successfully(): void
    {
        $data = new NotificationSendData('test@example.com', 'Test message');

        $this->resolver->method('resolve')
            ->with('test@example.com')
            ->willReturn(NotificationChannelType::EMAIL);

        $sender = $this->createMock(SenderInterface::class);
        $sender->method('send')
            ->with('test@example.com', 'Test message')
            ->willReturn(ChannelResponseData::success());

        $this->factory->method('make')
            ->with(NotificationChannelType::EMAIL)
            ->willReturn($sender);

        $this->eventDispatcher->expects($this->once())
            ->method('dispatchSent')
            ->with(
                recipient: 'test@example.com',
                message: 'Test message',
                channel: NotificationChannelType::EMAIL
            );

        $response = $this->gateway->send($data);

        $this->assertInstanceOf(NotificationResponseData::class, $response);
        $this->assertEquals(NotificationChannelType::EMAIL, $response->channel);
        $this->assertEquals(ChannelResponseStatus::SUCCESS, $response->status);
        $this->assertEquals('Test message', $response->sentMessage);
    }

    public function test_it_throws_exception_for_unknown_channel_type(): void
    {
        $data = new NotificationSendData('invalid', 'Test message');

        $this->resolver->method('resolve')
            ->with('invalid')
            ->willReturn(NotificationChannelType::UNKNOWN);

        $this->expectException(NotificationChannelException::class);
        $this->expectExceptionMessage('Unknown notification channel type');

        $this->gateway->send($data);
    }

    public function test_it_dispatches_failed_event_on_sender_exception(): void
    {
        $data = new NotificationSendData('test@example.com', 'Test message');

        $this->resolver->method('resolve')
            ->willReturn(NotificationChannelType::EMAIL);

        $sender = $this->createMock(SenderInterface::class);
        $sender->method('send')
            ->willThrowException(new NotificationChannelException('Connection failed'));

        $this->factory->method('make')
            ->willReturn($sender);

        $this->eventDispatcher->expects($this->once())
            ->method('dispatchFailed')
            ->with(
                recipient: 'test@example.com',
                message: 'Test message',
                channel: NotificationChannelType::EMAIL,
                error: 'Connection failed'
            );

        $this->expectException(NotificationChannelException::class);

        $this->gateway->send($data);
    }

    public function test_it_sends_phone_notification(): void
    {
        $data = new NotificationSendData('+380123456789', 'SMS message');

        $this->resolver->method('resolve')
            ->with('+380123456789')
            ->willReturn(NotificationChannelType::PHONE);

        $sender = $this->createMock(SenderInterface::class);
        $sender->method('send')
            ->willReturn(ChannelResponseData::success());

        $this->factory->method('make')
            ->with(NotificationChannelType::PHONE)
            ->willReturn($sender);

        $this->eventDispatcher->expects($this->once())
            ->method('dispatchSent')
            ->with(
                recipient: '+380123456789',
                message: 'SMS message',
                channel: NotificationChannelType::PHONE
            );

        $response = $this->gateway->send($data);

        $this->assertEquals(NotificationChannelType::PHONE, $response->channel);
    }

    public function test_it_does_not_dispatch_sent_event_on_failure(): void
    {
        $data = new NotificationSendData('test@example.com', 'Test message');

        $this->resolver->method('resolve')
            ->willReturn(NotificationChannelType::EMAIL);

        $sender = $this->createMock(SenderInterface::class);
        $sender->method('send')
            ->willThrowException(new NotificationChannelException('Failed'));

        $this->factory->method('make')
            ->willReturn($sender);

        $this->eventDispatcher->expects($this->never())
            ->method('dispatchSent');

        $this->eventDispatcher->expects($this->once())
            ->method('dispatchFailed');

        try {
            $this->gateway->send($data);
        } catch (NotificationChannelException $e) {
            // Expected
        }
    }

    public function test_it_resolves_channel_type_before_creating_sender(): void
    {
        $data = new NotificationSendData('test@example.com', 'Test message');

        $this->resolver->expects($this->once())
            ->method('resolve')
            ->with('test@example.com')
            ->willReturn(NotificationChannelType::EMAIL);

        $sender = $this->createMock(SenderInterface::class);
        $sender->method('send')
            ->willReturn(ChannelResponseData::success());

        $this->factory->expects($this->once())
            ->method('make')
            ->with(NotificationChannelType::EMAIL)
            ->willReturn($sender);

        $this->gateway->send($data);
    }

    public function test_it_propagates_exception_after_dispatching_failed_event(): void
    {
        $data = new NotificationSendData('test@example.com', 'Test message');

        $this->resolver->method('resolve')
            ->willReturn(NotificationChannelType::EMAIL);

        $exception = new NotificationChannelException('Test error');
        $sender = $this->createMock(SenderInterface::class);
        $sender->method('send')
            ->willThrowException($exception);

        $this->factory->method('make')
            ->willReturn($sender);

        $this->expectException(NotificationChannelException::class);
        $this->expectExceptionMessage('Test error');

        $this->gateway->send($data);
    }
}
