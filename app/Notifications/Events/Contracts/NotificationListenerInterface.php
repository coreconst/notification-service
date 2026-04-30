<?php

namespace App\Notifications\Events\Contracts;

use App\Notifications\Events\Data\NotificationFailed;
use App\Notifications\Events\Data\NotificationSent;

interface NotificationListenerInterface
{
    public function handleSent(NotificationSent $event): void;

    public function handleFailed(NotificationFailed $event): void;
}
