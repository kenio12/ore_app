<?php

namespace App\Modules\AppV2\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Modules\AppV2\Models\Screenshot;
use App\Modules\AppV2\Models\Hardware;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class App extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'apps_v2';

    // ==================== 01 基本情報フィールド ====================
    protected $basic_fields = [
        'user_id',          // 作者のID
        'title',            // アプリ名
        'description',      // アプリの概要
        'motivation',       // このアプリを作るきっかけ
        'purpose',          // このアプリの目指すところ
        'demo_url',         // デモURL
        'github_url',       // GitHubリポジトリURL
        'status',          // 公開状態
        'app_types',       // アプリの種類
        'genres',          // ジャンル
        'app_status',      // 開発状況
        'development_period_years',    // 開発期間（年）
        'development_period_months',   // 開発期間（月）
        'development_start_date',      // 開発開始日
        'development_end_date',        // 開発終了日
    ];

    // ==================== 02 スクリーンショット関連 ====================
    protected $screenshot_fields = [
        'data',  // スクリーンショットデータ
    ];

    // ==================== 03 開発ストーリー関連 ====================
    protected $story_fields = [
        'development_trigger',      // 開発のきっかけ
        'development_hardship',     // 開発の苦労話
        'development_tearful',      // 開発の泣ける話
        'development_enjoyable',    // 開発の楽しかった話
        'development_funny',        // 開発の笑える話
        'development_impression',   // 開発を通しての気づき
        'development_oneword',      // 開発を終えての一言
    ];

    // ==================== 04 ハードウェア関連 ====================
    protected $hardware_fields = [
        'hardware_info',  // ハードウェア要件情報
    ];

    // ==================== 05 開発環境関連 ====================
    protected $dev_env_fields = [
        'dev_env_info',  // 開発環境情報
    ];

    // ==================== 06 アーキテクチャ関連 ====================
    protected $architecture_fields = [
        'architecture_info',  // アーキテクチャ情報
    ];

    // ==================== 07 フロントエンド関連 ====================
    protected $frontend_fields = [
        'frontend_info',  // フロントエンド情報
    ];

    // ==================== 08 バックエンド関連 ====================
    protected $backend_fields = [
        'backend_info',  // バックエンド情報
    ];

    // ==================== 09 データベース関連 ====================
    protected $database_fields = [
        'database_info',  // データベース情報
    ];

    // ==================== 10 セキュリティ関連 ====================
    protected $security_fields = [
        'security_info',  // セキュリティ情報
    ];

    // ==================== 基本プロパティ ====================
    protected $fillable = [
        // 01 基本情報フィールド
        'user_id',          // 作者のID
        'title',            // アプリ名
        'description',      // アプリの概要
        'motivation',       // このアプリを作るきっかけ
        'purpose',          // このアプリの目指すところ
        'demo_url',         // デモURL
        'github_url',       // GitHubリポジトリURL
        'status',          // 公開状態
        'color',           // カラーテーマ
        'completed_sections', // 完了セクション
        'app_types',       // アプリの種類
        'genres',          // ジャンル
        'app_status',      // 開発状況

        // 02 スクリーンショット関連
        'data',            // スクリーンショットデータ

        // 03 開発ストーリー関連
        'development_trigger',      // 開発のきっかけ
        'development_hardship',     // 開発の苦労話
        'development_tearful',      // 開発の泣ける話
        'development_enjoyable',    // 開発の楽しかった話
        'development_funny',        // 開発の笑える話
        'development_impression',   // 開発を通しての気づき
        'development_oneword',      // 開発を終えての一言

        // 04 開発期間関連
        'development_period_years',    // 開発期間（年）
        'development_period_months',   // 開発期間（月）
        'development_start_date',      // 開発開始日
        'development_end_date',        // 開発終了日

        // 05 技術スタック関連
        'hardware_info',     // ハードウェア要件情報
        'dev_env_info',      // 開発環境情報
        'architecture_info', // アーキテクチャ情報
        'frontend_info',     // フロントエンド情報
        'backend_info',      // バックエンド情報
        'database_info',     // データベース情報
        'security_info',     // セキュリティ情報
    ];

    // ==================== 基本の開発環境関連 ====================
    
    protected $development_env_fields = [
        'development_period_years',
        'development_period_months',
        'development_start_date',
        'development_end_date',
        'motivation',
        'purpose'
    ];

    // ==================== 技術スタック関連 ====================
    protected $tech_stack_fields = [
        'hardware_info',
        'dev_env_info',
        'architecture_info',
        'security_info',
        'frontend_info',
        'backend_info',
        'database_info'
    ];

    // ==================== JSON変換設定 ====================
    protected $casts = [
        'completed_sections' => 'array',
        'app_types' => 'array',
        'genres' => 'array',
        'hardware_info' => 'array',
        'dev_env_info' => 'array',
        'architecture_info' => 'array',
        'security_info' => 'array',
        'frontend_info' => 'array',
        'backend_info' => 'array',
        'database_info' => 'array',
        'development_start_date' => 'date',
        'development_end_date' => 'date',
        'data' => 'array',
    ];

    // Eagerローディングを設定
    protected $with = ['user'];

    /**
     * ユーザーとのリレーション
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
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

    // ==================== 開発関連のメソッド ====================
    public function getDevelopmentPeriod()
    {
        return [
            'years' => $this->development_period_years,
            'months' => $this->development_period_months
        ];
    }

    public function getDevelopmentStory()
    {
        return [
            'motivation' => $this->motivation,
            'purpose' => $this->purpose,
            'trigger' => $this->development_trigger,
            'hardship' => $this->development_hardship,
            'tearful' => $this->development_tearful,
            'enjoyable' => $this->development_enjoyable,
            'funny' => $this->development_funny,
            'impression' => $this->development_impression,
            'oneword' => $this->development_oneword
        ];
    }

    // ==================== ユーティリティメソッド ====================
    public function getUserName()
    {
        Log::debug('Getting user name:', [
            'app_id' => $this->id,
            'user_id' => $this->user_id,
            'user' => $this->user,
            'user_name' => $this->user->name ?? 'null'
        ]);
        return $this->user->name ?? null;
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