<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Device extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'image_url',
        'store_url',
        'protocol',
        'drm',
        'area',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn() => Storage::url($this->image_url),
        );
    }
}
