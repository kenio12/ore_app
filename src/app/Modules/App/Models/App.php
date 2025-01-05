<?php

namespace App\Modules\App\Models;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    protected $fillable = [
        'title',
        'description',
        'publish_status',
        'demo_url',
        'source_url',
        'development_period_years',
        'development_period_months',
        'app_type',
        'genres',
    ];

    protected $casts = [
        'genres' => 'array',
    ];
} 