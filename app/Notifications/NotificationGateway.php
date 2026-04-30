<?php

namespace App\Notifications;

use App\Notifications\Channel\Contracts\Type\ChannelTypeResolverInterface;
use App\Notifications\Contracts\NotificationGatewayInterface;
use App\Notifications\Data\NotificationResponseData;
use App\Notifications\Data\NotificationSendData;
use App\Notifications\Enum\NotificationChannelType;

class NotificationGateway implements NotificationGatewayInterface
{
    public function __construct(
        private ChannelTypeResolverInterface $channelTypeResolver,
    ){}

    public function send(NotificationSendData $data): NotificationResponseData
    {
        $channelType = $this->channelTypeResolver->resolve($data->to);
        if($channelType === NotificationChannelType::UNKNOWN){
            throw new \InvalidArgumentException('Unknown channel type');
        }


        return new NotificationResponseData($channelType, $data->message);
    }
}
