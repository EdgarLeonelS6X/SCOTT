<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportDetail extends Model
{
    protected $fillable = [
        'report_id',
        'channel_id',
        'stage_id',
        'subcategory',
        'protocol',
        'media',
        'description',
        'status',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function reportContentLosses()
    {
        return $this->hasMany(ReportContentLoss::class);
    }
}
