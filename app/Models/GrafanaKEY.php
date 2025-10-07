<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrafanaKEY extends Model
{
    protected $fillable = [
        'key',
    ];

    public function url()
    {
        return $this->belongsTo(GrafanaURL::class, 'url_id');
    }
}
