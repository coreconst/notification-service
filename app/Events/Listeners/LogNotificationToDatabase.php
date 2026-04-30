<?php

namespace App\Events\Listeners;

use App\Models\NotificationHistory;
use App\Notifications\Channel\Enum\ChannelResponseStatus;
use App\Notifications\Events\Contracts\NotificationListenerInterface;
use App\Notifications\Events\Data\NotificationFailed;
use App\Notifications\Events\Data\NotificationSent;

class LogNotificationToDatabase implements NotificationListenerInterface
{
    public function handleSent(NotificationSent $event): void
    {
        NotificationHistory::create([
            'recipient' => $event->recipient,
            'channel' => $event->channel->value,
            'message' => $event->message,
            'status' => ChannelResponseStatus::SUCCESS->value,
            'sent_at' => $event->sentAt,
        ]);
    }

    public function handleFailed(NotificationFailed $event): void
    {
        NotificationHistory::create([
            'recipient' => $event->recipient,
            'channel' => $event->channel->value,
            'message' => $event->message,
            'error' => $event->error,
            'status' => ChannelResponseStatus::ERROR->value,
            'sent_at' => $event->failedAt,
        ]);
    }
}
