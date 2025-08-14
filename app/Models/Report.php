<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $timestamps = true;

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

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function stages()
    {
        return $this->hasManyThrough(Stage::class, ReportDetail::class, 'report_id', 'id', 'id', 'stage_id');
    }

    public function reportContentLosses()
    {
        return $this->hasManyThrough(
            ReportContentLoss::class,
            ReportDetail::class,
            'report_id',
            'report_detail_id',
            'id',
            'id'
        );
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
