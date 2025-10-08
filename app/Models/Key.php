<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    protected $fillable = [
        'key',
        'url_id',
    ];

    public function url()
    {
        return $this->belongsTo(Url::class, 'url_id');
    }
}
