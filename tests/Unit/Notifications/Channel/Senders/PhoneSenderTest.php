<?php

namespace Tests\Unit\Notifications\Channel\Senders;

use App\Notifications\Channel\Data\ChannelResponseData;
use App\Notifications\Channel\Enum\ChannelResponseStatus;
use App\Notifications\Channel\Exceptions\Senders\PhoneNotificationException;
use App\Notifications\Channel\Senders\PhoneSender;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PhoneSenderTest extends TestCase
{
    private PhoneSender $sender;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sender = new PhoneSender('https://test.example.com');
    }

    public function test_it_sends_sms_successfully(): void
    {
        Http::fake([
            'https://test.example.com' => Http::response([], 200)
        ]);

        $response = $this->sender->send('+380123456789', 'Test message');

        $this->assertInstanceOf(ChannelResponseData::class, $response);
        $this->assertEquals(ChannelResponseStatus::SUCCESS, $response->status);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://test.example.com' &&
                   $request['phone'] === '+380123456789' &&
                   $request['message'] === 'Test message';
        });
    }

    public function test_it_throws_exception_on_http_error(): void
    {
        Http::fake([
            'https://test.example.com' => Http::response([], 404)
        ]);

        $this->expectException(PhoneNotificationException::class);

        $this->sender->send('+380123456789', 'Test message');
    }

    public function test_it_throws_exception_on_connection_error(): void
    {
        Http::fake(function () {
            throw new ConnectionException('Network error');
        });

        $this->expectException(PhoneNotificationException::class);

        $this->sender->send('+380123456789', 'Test message');
    }

    public function test_it_sends_correct_payload_structure(): void
    {
        Http::fake([
            'https://test.example.com' => Http::response([], 200)
        ]);

        $this->sender->send('+1234567890', 'SMS text');

        Http::assertSent(function ($request) {
            $data = $request->data();
            return isset($data['phone']) &&
                   isset($data['message']) &&
                   $data['phone'] === '+1234567890' &&
                   $data['message'] === 'SMS text';
        });
    }
}
