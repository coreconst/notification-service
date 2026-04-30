<?php

namespace App\Notifications\Channel\Factory;

use App\Notifications\Channel\Contracts\SenderFactoryInterface;
use App\Notifications\Channel\Contracts\SenderInterface;
use App\Notifications\Channel\Exceptions\NotificationChannelException;
use App\Notifications\Channel\Senders\EmailSender;
use App\Notifications\Channel\Senders\PhoneSender;
use App\Notifications\Enum\NotificationChannelType;

class SenderFactory implements SenderFactoryInterface
{
    public function __construct(
        private PhoneSender $phoneSender,
        private EmailSender $emailSender
    ){}

    /**
     * @throws NotificationChannelException
     */
    public function make(NotificationChannelType $type): SenderInterface
    {
        return match ($type) {
            NotificationChannelType::EMAIL => $this->emailSender,
            NotificationChannelType::PHONE => $this->phoneSender,
            default => throw new NotificationChannelException("Type {$type->value} is not supported")
        };
    }
}
