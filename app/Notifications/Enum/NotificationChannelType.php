<?php

namespace App\Notifications\Enum;

enum NotificationChannelType: string
{
    case SMS = 'sms';

    case EMAIL = 'email';

    case UNKNOWN = '';
}
