<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Channel extends Model
{
    protected $fillable = [
        "image_url",
        "number",
        "area",
        "origin",
        "name",
        "url",
        "category",
        "profiles",
        "status",
    ];

    protected $casts = [
        'profiles' => 'array',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn() => Storage::url($this->image_url),
        );
    }

    public function reportDetails()
    {
        return $this->hasMany(ReportDetail::class);
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }
}
