<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chromecast extends Model
{
    protected $fillable = [
        'report_id',
        'description',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
