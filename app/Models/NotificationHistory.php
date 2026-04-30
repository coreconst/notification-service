<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'recipient',
        'channel',
        'message',
        'status',
        'error',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
