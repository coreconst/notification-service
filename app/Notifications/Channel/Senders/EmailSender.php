<?php

namespace App\Notifications\Channel\Senders;

use App\Notifications\Channel\Contracts\SenderInterface;
use App\Notifications\Channel\Exceptions\NotificationChannelException;
use App\Notifications\Channel\Exceptions\Senders\EmailNotificationException;

class EmailSender extends AbstractSender implements SenderInterface
{
    protected function providerPayload(string $recipient, string $text): array
    {
        return [
            'email' => $recipient,
            'message' => $text
        ];
    }

    protected function providerException(string $message, int $code = 0, ?\Throwable $previous = null): NotificationChannelException
    {
        return new EmailNotificationException($message, $code, $previous);
    }
}
