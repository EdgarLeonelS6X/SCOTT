<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrafanaPanel extends Model
{
    use HasFactory;

    protected $table = 'grafana_panels';

    protected $fillable = [
        'name',
        'url',
        'api_url',
        'endpoint',
        'api_key',
    ];
}
