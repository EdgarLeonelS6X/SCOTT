<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelTest extends Model
{
    protected $fillable = [
        "channel_id",
        "user_id",
        "high",
        "medium",
        "low",
    ];
}
