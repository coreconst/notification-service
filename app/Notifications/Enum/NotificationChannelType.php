<?php

namespace App\Notifications\Enum;

enum NotificationChannelType: string
{
    case PHONE = 'phone';

    case EMAIL = 'email';

    case UNKNOWN = 'unknown';
}
