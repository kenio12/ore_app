<?php

namespace App\Modules\AppV2\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Modules\AppV2\Models\Screenshot;
use App\Modules\AppV2\Models\Hardware;

class App extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'apps_v2';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'screenshots',
        'demo_url',
        'github_url',
        'status',
        'color',
        'completed_sections',
        'app_types',
        'genres',
        'app_status',
        'development_period_years',
        'development_period_months',
        'development_start_date',
        'development_end_date',
        'data',
        'hardware_info',
        'dev_env_info',
        'architecture_info',
        'security_info',
        'frontend_info',
        'backend_info',
        'database_info',
        'motivation',
        'purpose',
    ];

    protected $casts = [
        'screenshots' => 'array',
        'completed_sections' => 'array',
        'app_types' => 'array',
        'genres' => 'array',
        'development_start_date' => 'date',
        'development_end_date' => 'date',
        'hardware_info' => 'array',
        'dev_env_info' => 'array',
        'architecture_info' => 'array',
        'security_info' => 'array',
        'frontend_info' => 'array',
        'backend_info' => 'array',
        'database_info' => 'array',
        'data' => 'array',
    ];

    // リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hardware()
    {
        return $this->hasOne(Hardware::class);
    }

    public function story()
    {
        return $this->hasOne(Story::class);
    }

    public function devTools()
    {
        return $this->hasOne(DevTools::class);
    }

    public function basicDevEnvironment()
    {
        return $this->hasOne(BasicDevEnvironment::class);
    }

    public function architecture()
    {
        return $this->hasOne(Architecture::class);
    }

    public function security()
    {
        return $this->hasOne(Security::class);
    }

    public function backend()
    {
        return $this->hasOne(Backend::class);
    }

    public function frontend()
    {
        return $this->hasOne(Frontend::class);
    }

    public function database()
    {
        return $this->hasOne(Database::class);
    }

    public function screenshots()
    {
        return $this->hasMany(Screenshot::class);
    }

    // ヘルパーメソッド
    public function isCompleted(string $section): bool
    {
        return in_array($section, $this->completed_sections ?? []);
    }

    public function markSectionAsCompleted(string $section): void
    {
        $completed = $this->completed_sections ?? [];
        if (!in_array($section, $completed)) {
            $completed[] = $section;
            $this->completed_sections = $completed;
            $this->save();
        }
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function publish(): void
    {
        $this->status = 'published';
        $this->save();
    }

    public function unpublish(): void
    {
        $this->status = 'draft';
        $this->save();
    }

    // スコープ
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // データ取得用のアクセサ
    public function getBasicDataAttribute()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'app_types' => $this->app_types,
            'genres' => $this->genres,
            'app_status' => $this->app_status,
            'demo_url' => $this->demo_url,
            'github_url' => $this->github_url,
            'development_start_date' => $this->development_start_date,
            'development_end_date' => $this->development_end_date,
            'development_period_years' => $this->development_period_years,
            'development_period_months' => $this->development_period_months,
        ];
    }
} 