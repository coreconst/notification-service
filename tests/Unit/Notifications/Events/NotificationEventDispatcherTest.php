<?php

namespace Tests\Unit\Notifications\Events;

use App\Notifications\Enum\NotificationChannelType;
use App\Notifications\Events\Contracts\NotificationListenerInterface;
use App\Notifications\Events\Data\NotificationSent;
use App\Notifications\Events\NotificationEventDispatcher;
use App\Notifications\Events\Data\NotificationFailed;
use PHPUnit\Framework\TestCase;

class NotificationEventDispatcherTest extends TestCase
{
    private NotificationEventDispatcher $dispatcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dispatcher = new NotificationEventDispatcher();
    }

    public function test_it_registers_listener(): void
    {
        $listener = $this->createMock(NotificationListenerInterface::class);

        $this->dispatcher->register($listener);

        $this->assertTrue(true);
    }

    public function test_it_dispatches_sent_event_to_registered_listeners(): void
    {
        $listener = $this->createMock(NotificationListenerInterface::class);
        $listener->expects($this->once())
            ->method('handleSent')
            ->with($this->callback(function ($event) {
                return $event instanceof NotificationSent &&
                       $event->recipient === 'test@example.com' &&
                       $event->message === 'Test message' &&
                       $event->channel === NotificationChannelType::EMAIL;
            }));

        $this->dispatcher->register($listener);

        $this->dispatcher->dispatchSent(
            recipient: 'test@example.com',
            message: 'Test message',
            channel: NotificationChannelType::EMAIL
        );
    }

    public function test_it_dispatches_failed_event_to_registered_listeners(): void
    {
        $listener = $this->createMock(NotificationListenerInterface::class);
        $listener->expects($this->once())
            ->method('handleFailed')
            ->with($this->callback(function ($event) {
                return $event instanceof NotificationFailed &&
                       $event->recipient === 'test@example.com' &&
                       $event->message === 'Test message' &&
                       $event->channel === NotificationChannelType::EMAIL &&
                       $event->error === 'Connection failed';
            }));

        $this->dispatcher->register($listener);

        $this->dispatcher->dispatchFailed(
            recipient: 'test@example.com',
            message: 'Test message',
            channel: NotificationChannelType::EMAIL,
            error: 'Connection failed'
        );
    }

    public function test_it_dispatches_to_multiple_listeners(): void
    {
        $listener1 = $this->createMock(NotificationListenerInterface::class);
        $listener1->expects($this->once())->method('handleSent');

        $listener2 = $this->createMock(NotificationListenerInterface::class);
        $listener2->expects($this->once())->method('handleSent');

        $this->dispatcher->register($listener1);
        $this->dispatcher->register($listener2);

        $this->dispatcher->dispatchSent(
            recipient: 'test@example.com',
            message: 'Test message',
            channel: NotificationChannelType::EMAIL
        );
    }

    public function test_it_creates_event_with_current_timestamp(): void
    {
        $listener = $this->createMock(NotificationListenerInterface::class);
        $listener->expects($this->once())
            ->method('handleSent')
            ->with($this->callback(function ($event) {
                $now = new \DateTimeImmutable();
                $diff = $now->getTimestamp() - $event->sentAt->getTimestamp();
                return $diff >= 0 && $diff < 2;
            }));

        $this->dispatcher->register($listener);

        $this->dispatcher->dispatchSent(
            recipient: 'test@example.com',
            message: 'Test message',
            channel: NotificationChannelType::EMAIL
        );
    }

    public function test_it_does_not_fail_when_no_listeners_registered(): void
    {
        $this->dispatcher->dispatchSent(
            recipient: 'test@example.com',
            message: 'Test message',
            channel: NotificationChannelType::EMAIL
        );

        $this->dispatcher->dispatchFailed(
            recipient: 'test@example.com',
            message: 'Test message',
            channel: NotificationChannelType::EMAIL,
            error: 'Error'
        );

        $this->assertTrue(true);
    }

    public function test_it_dispatches_phone_channel_events(): void
    {
        $listener = $this->createMock(NotificationListenerInterface::class);
        $listener->expects($this->once())
            ->method('handleSent')
            ->with($this->callback(function ($event) {
                return $event->channel === NotificationChannelType::PHONE &&
                       $event->recipient === '+380123456789';
            }));

        $this->dispatcher->register($listener);

        $this->dispatcher->dispatchSent(
            recipient: '+380123456789',
            message: 'SMS message',
            channel: NotificationChannelType::PHONE
        );
    }

    public function test_sent_event_is_readonly(): void
    {
        $listener = $this->createMock(NotificationListenerInterface::class);
        $listener->expects($this->once())
            ->method('handleSent')
            ->with($this->callback(function ($event) {
                $reflection = new \ReflectionClass($event);
                return $reflection->isReadOnly();
            }));

        $this->dispatcher->register($listener);

        $this->dispatcher->dispatchSent(
            recipient: 'test@example.com',
            message: 'Test message',
            channel: NotificationChannelType::EMAIL
        );
    }

    public function test_failed_event_is_readonly(): void
    {
        $listener = $this->createMock(NotificationListenerInterface::class);
        $listener->expects($this->once())
            ->method('handleFailed')
            ->with($this->callback(function ($event) {
                $reflection = new \ReflectionClass($event);
                return $reflection->isReadOnly();
            }));

        $this->dispatcher->register($listener);

        $this->dispatcher->dispatchFailed(
            recipient: 'test@example.com',
            message: 'Test message',
            channel: NotificationChannelType::EMAIL,
            error: 'Error'
        );
    }
}
