<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Radio extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'radios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'status',
        'area',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Set sensible defaults when creating.
     */
    protected static function booted()
    {
        static::creating(function ($radio) {
            if (empty($radio->area)) {
                $radio->area = 'DTH';
            }
            if (! isset($radio->status)) {
                $radio->status = true;
            }
        });
    }
}
