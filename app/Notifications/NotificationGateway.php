<?php

namespace App\Notifications;

use App\Notifications\Channel\Contracts\SenderFactoryInterface;
use App\Notifications\Channel\Contracts\Type\ChannelTypeResolverInterface;
use App\Notifications\Channel\Exceptions\NotificationChannelException;
use App\Notifications\Contracts\NotificationGatewayInterface;
use App\Notifications\Data\NotificationResponseData;
use App\Notifications\Data\NotificationSendData;
use App\Notifications\Enum\NotificationChannelType;
use App\Notifications\Events\NotificationEventDispatcher;

class NotificationGateway implements NotificationGatewayInterface
{
    public function __construct(
        private ChannelTypeResolverInterface $channelTypeResolver,
        private SenderFactoryInterface $senderFactory,
        private NotificationEventDispatcher $eventDispatcher,
    ){}

    /**
     * @throws NotificationChannelException
     */
    public function send(NotificationSendData $data): NotificationResponseData
    {
        $channelType = $this->channelTypeResolver->resolve($data->to);
        if($channelType === NotificationChannelType::UNKNOWN){
            throw new NotificationChannelException('Unknown notification channel type');
        }

        $sender = $this->senderFactory->make($channelType);

        try {
            $res = $sender->send($data->to, $data->message);

            $this->eventDispatcher->dispatchSent(
                recipient: $data->to,
                message: $data->message,
                channel: $channelType
            );

            return new NotificationResponseData(
                $channelType,
                $res->status,
                $data->message
            );

        } catch (NotificationChannelException $e) {
            $this->eventDispatcher->dispatchFailed(
                recipient: $data->to,
                message: $data->message,
                channel: $channelType,
                error: $e->getMessage()
            );

            throw $e;
        }
    }
}
