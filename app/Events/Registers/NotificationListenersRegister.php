<?php

namespace App\Events\Registers;

use App\Events\Listeners\LogNotificationToDatabase;

class NotificationListenersRegister
{
    public static function listeners(): array
    {
        return [
            new LogNotificationToDatabase()
        ];
    }
}
