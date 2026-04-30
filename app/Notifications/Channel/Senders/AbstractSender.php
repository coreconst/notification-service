<?php

namespace App\Notifications\Channel\Senders;

use App\Notifications\Channel\Contracts\SenderInterface;
use App\Notifications\Channel\Data\ChannelResponseData;
use App\Notifications\Channel\Exceptions\NotificationChannelException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

abstract class AbstractSender implements SenderInterface
{
    public function __construct(
        protected string $url
    ){}

    public function send(string $recipient, string $text): ChannelResponseData
    {
        try {
            $response = Http::timeout(10)
                ->retry(3, 100)
                ->post($this->url, $this->providerPayload($recipient, $text));

            if ($response->failed()) {
                throw $this->providerException(
                    "Failed to send: HTTP {$response->status()}"
                );
            }

            return ChannelResponseData::success();
        } catch (ConnectionException $e) {
            throw $this->providerException(
                "Connection failed: {$e->getMessage()}",
                0,
                $e
            );
        } catch (NotificationChannelException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $this->providerException($e->getMessage(), $e->getCode(), $e);
        }
    }

    abstract protected function providerPayload(string $recipient, string $text): array;

    abstract protected function providerException(
        string $message,
        int $code = 0,
        ?\Throwable $previous = null
    ): NotificationChannelException;
}
