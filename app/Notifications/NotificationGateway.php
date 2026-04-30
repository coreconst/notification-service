<?php

namespace App\Notifications;

use App\Notifications\Channel\Resolvers\ChannelTypeResolver;
use App\Notifications\Contracts\NotificationGatewayInterface;
use App\Notifications\Data\NotificationResponseData;
use App\Notifications\Data\NotificationSendData;
use App\Notifications\Enum\NotificationChannelType;

class NotificationGateway implements NotificationGatewayInterface
{
    public function __construct(
    )
    {}

    public function send(NotificationSendData $data): NotificationResponseData
    {
        return new NotificationResponseData(NotificationChannelType::EMAIL, $data->message);
    }
}
