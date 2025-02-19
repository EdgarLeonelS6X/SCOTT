<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'type',
        'category',
        'duration',
        'reported_by',
        'reviewed_by',
        'attended_by',
        'status',
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

    public function attendedBy()
    {
        return $this->belongsTo(User::class, 'attended_by');
    }

    public function stages()
    {
        return $this->hasManyThrough(Stage::class, ReportDetail::class, 'report_id', 'id', 'id', 'stage_id');
    }
}