<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelTest extends Model
{
    protected $table = 'video_profile_tests';

    protected $fillable = [
        "report_id",
        "channel_id",
        "user_id",
        "high",
        "medium",
        "low",
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}
