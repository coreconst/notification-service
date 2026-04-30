<?php

namespace Tests\Unit\Notifications\Channel\Senders;

use App\Notifications\Channel\Data\ChannelResponseData;
use App\Notifications\Channel\Enum\ChannelResponseStatus;
use App\Notifications\Channel\Exceptions\Senders\EmailNotificationException;
use App\Notifications\Channel\Senders\EmailSender;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EmailSenderTest extends TestCase
{
    private EmailSender $sender;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sender = new EmailSender('https://test.example.com');
    }

    public function test_it_sends_email_successfully(): void
    {
        Http::fake([
            'https://test.example.com' => Http::response([], 200)
        ]);

        $response = $this->sender->send('test@example.com', 'Test message');

        $this->assertInstanceOf(ChannelResponseData::class, $response);
        $this->assertEquals(ChannelResponseStatus::SUCCESS, $response->status);
    }

    public function test_it_throws_exception_on_http_error(): void
    {
        Http::fake([
            'https://test.example.com' => Http::response([], 500)
        ]);

        $this->expectException(EmailNotificationException::class);

        $this->sender->send('test@example.com', 'Test message');
    }

    public function test_it_throws_exception_on_connection_error(): void
    {
        Http::fake(function () {
            throw new ConnectionException('Connection timeout');
        });

        $this->expectException(EmailNotificationException::class);

        $this->sender->send('test@example.com', 'Test message');
    }

    public function test_it_retries_on_failure(): void
    {
        Http::fake([
            'https://test.example.com' => Http::sequence()
                ->push([], 500)
                ->push([], 500)
                ->push([], 200)
        ]);

        $response = $this->sender->send('test@example.com', 'Test message');

        $this->assertEquals(ChannelResponseStatus::SUCCESS, $response->status);

        Http::assertSentCount(3);
    }

    public function test_it_sends_correct_payload_structure(): void
    {
        Http::fake([
            'https://test.example.com' => Http::response([], 200)
        ]);

        $this->sender->send('user@test.com', 'Hello World');

        Http::assertSent(function ($request) {
            $data = $request->data();
            return isset($data['email']) &&
                   isset($data['message']) &&
                   $data['email'] === 'user@test.com' &&
                   $data['message'] === 'Hello World';
        });
    }
}
