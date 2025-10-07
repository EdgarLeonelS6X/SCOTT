<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrafanaURL extends Model
{
    protected $fillable = [
        'name',
        'url',
        'key_id',
    ];

    public function key()
    {
        return $this->hasOne(GrafanaKEY::class, 'url_id');
    }
}
