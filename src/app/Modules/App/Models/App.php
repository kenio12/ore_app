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
        'security_measures',
        'performance_optimizations',
        'testing_tools',
        'monitoring_tools',
        'code_quality_tools',
        'security_notes',
        'dev_team_size',
        'virtualization',
        'other_virtualization',
        'os_type',
        'os_version',
        'editors',
        'other_editor',
        'version_control',
        'other_version_control',
        'monitor_count',
        'main_monitor_size',
        'main_monitor_resolution',
        'monitor_details',
        'dev_env_notes',
        'user_id',
        'progress'
    ];

    protected $casts = [
        'screenshots' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'genres' => 'array',
        'security_measures' => 'array',
        'performance_optimizations' => 'array',
        'testing_tools' => 'array',
        'monitoring_tools' => 'array',
        'code_quality_tools' => 'array',
        'virtualization' => 'array',
        'editors' => 'array',
        'version_control' => 'array',
        'progress' => 'array'
    ];

    // ユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
} 