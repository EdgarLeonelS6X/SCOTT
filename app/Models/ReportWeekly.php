<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportWeekly extends Model
{
    protected $fillable = [
        'report_id',
        'user_id',
        'title',
        'description',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
