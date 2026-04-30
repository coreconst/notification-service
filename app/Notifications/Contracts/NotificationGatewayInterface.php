<?php

namespace App\Notifications\Contracts;

use App\Notifications\Data\NotificationResponseData;
use App\Notifications\Data\NotificationSendData;

interface NotificationGatewayInterface
{
    public function send(NotificationSendData $data): NotificationResponseData;
}
