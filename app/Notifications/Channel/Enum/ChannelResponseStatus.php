<?php

namespace App\Notifications\Channel\Enum;

enum ChannelResponseStatus: string
{
    case SUCCESS = 'success';

    case ERROR = 'error';
}
