<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = [
        'name',
        'url',
    ];

    public function key()
    {
        return $this->hasOne(Key::class, 'url_id');
    }
}
