<?php

namespace Tests\Feature\Http\Controllers;

use App\Notifications\Channel\Enum\ChannelResponseStatus;
use App\Notifications\Channel\Exceptions\NotificationChannelException;
use App\Notifications\Contracts\NotificationGatewayInterface;
use App\Notifications\Data\NotificationResponseData;
use App\Notifications\Enum\NotificationChannelType;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    public function test_it_sends_notification_successfully(): void
    {
        $gateway = $this->createMock(NotificationGatewayInterface::class);
        $gateway->expects($this->once())
            ->method('send')
            ->willReturn(new NotificationResponseData(
                NotificationChannelType::EMAIL,
                ChannelResponseStatus::SUCCESS,
                'Test message'
            ));

        $this->app->instance(NotificationGatewayInterface::class, $gateway);

        $response = $this->postJson('/api/notification', [
            'to' => 'test@example.com',
            'message' => 'Test message'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'channel' => 'email',
                'status' => 'success',
                'message' => 'Test message'
            ]);
    }

    public function test_it_validates_required_fields(): void
    {
        $response = $this->postJson('/api/notification', []);

        $response->assertStatus(422);
    }

    public function test_it_validates_to_field_is_required(): void
    {
        $response = $this->postJson('/api/notification', [
            'message' => 'Test message'
        ]);

        $response->assertStatus(422);
    }

    public function test_it_validates_message_field_is_required(): void
    {
        $response = $this->postJson('/api/notification', [
            'to' => 'test@example.com'
        ]);

        $response->assertStatus(422);
    }

    public function test_it_sends_email_notification(): void
    {
        $gateway = $this->createMock(NotificationGatewayInterface::class);
        $gateway->method('send')
            ->willReturn(new NotificationResponseData(
                NotificationChannelType::EMAIL,
                ChannelResponseStatus::SUCCESS,
                'Email message'
            ));

        $this->app->instance(NotificationGatewayInterface::class, $gateway);

        $response = $this->postJson('/api/notification', [
            'to' => 'user@example.com',
            'message' => 'Email message'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'channel' => 'email',
                'message' => 'Email message'
            ]);
    }

    public function test_it_sends_phone_notification(): void
    {
        $gateway = $this->createMock(NotificationGatewayInterface::class);
        $gateway->method('send')
            ->willReturn(new NotificationResponseData(
                NotificationChannelType::PHONE,
                ChannelResponseStatus::SUCCESS,
                'SMS message'
            ));

        $this->app->instance(NotificationGatewayInterface::class, $gateway);

        $response = $this->postJson('/api/notification', [
            'to' => '+380123456789',
            'message' => 'SMS message'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'channel' => 'phone',
                'message' => 'SMS message'
            ]);
    }

    public function test_it_returns_error_response_on_exception(): void
    {
        $gateway = $this->createMock(NotificationGatewayInterface::class);
        $gateway->method('send')
            ->willThrowException(new NotificationChannelException('Unknown notification channel type'));

        $this->app->instance(NotificationGatewayInterface::class, $gateway);

        $response = $this->postJson('/api/notification', [
            'to' => 'invalid',
            'message' => 'Test message'
        ]);

        $response->assertStatus(500);
    }

    public function test_it_accepts_json_content_type(): void
    {
        $gateway = $this->createMock(NotificationGatewayInterface::class);
        $gateway->method('send')
            ->willReturn(new NotificationResponseData(
                NotificationChannelType::EMAIL,
                ChannelResponseStatus::SUCCESS,
                'Test'
            ));

        $this->app->instance(NotificationGatewayInterface::class, $gateway);

        $response = $this->json('POST', '/api/notification', [
            'to' => 'test@example.com',
            'message' => 'Test'
        ], ['Content-Type' => 'application/json']);

        $response->assertStatus(200);
    }

    public function test_it_validates_to_field_is_string(): void
    {
        $response = $this->postJson('/api/notification', [
            'to' => 123,
            'message' => 'Test message'
        ]);

        $response->assertStatus(422);
    }

    public function test_it_validates_message_field_is_string(): void
    {
        $response = $this->postJson('/api/notification', [
            'to' => 'test@example.com',
            'message' => 123
        ]);

        $response->assertStatus(422);
    }
}
