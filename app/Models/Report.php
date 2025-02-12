<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'type',
        'category',
        'report_date',
        'start_time',
        'end_time',
        'duration',
        'reported_by',
        'attended_by',
        'status',
        'solution',
    ];

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function reportDetails()
    {
        return $this->hasMany(ReportDetail::class);
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function stages()
    {
        return $this->hasManyThrough(Stage::class, ReportDetail::class, 'report_id', 'id', 'id', 'stage_id');
    }
}