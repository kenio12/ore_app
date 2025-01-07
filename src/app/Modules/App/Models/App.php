<?php

namespace App\Modules\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class App extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'publish_status',
        'demo_url',
        'github_url',
        'status',
        'screenshots',
        'source_url',
        'development_period_years',
        'development_period_months',
        'app_type',
        'genres',
    ];

    protected $casts = [
        'screenshots' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'genres' => 'array',
    ];

    // ユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
} 