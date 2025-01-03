<?php

namespace App\Modules\AppPost\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppPost extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'publish_status',
        'github_url',
        'demo_url',
        'motivation',
        'challenges',
        'devised_points',
        'learnings',
        'future_plans',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'screenshots' => 'array',
        'hardware_info' => 'array',
        'software_info' => 'array',
        'backend_info' => 'array',
        'frontend_info' => 'array',
        'database_info' => 'array',
        'architecture_info' => 'array',
        'other_info' => 'array',
    ];

    /**
     * 投稿者のリレーション
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 