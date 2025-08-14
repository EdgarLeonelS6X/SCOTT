<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportContentLoss extends Model
{
    protected $fillable = ['report_detail_id', 'start_time', 'end_time', 'duration'];

    public function reportDetail()
    {
        return $this->belongsTo(ReportDetail::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($loss) {
            if ($loss->start_time && $loss->end_time) {
                $loss->duration = \Carbon\Carbon::parse($loss->start_time)
                    ->diffInMinutes(\Carbon\Carbon::parse($loss->end_time));
            }
        });
    }
}
