<?php

namespace App\Modules\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class _01_BasicInfo extends Model
{
    use SoftDeletes;

    protected $table = 'apps';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'screenshots',
        'demo_url',
        'github_url',
        'status',
        'app_types',
        'genres',
        'app_status',
        'development_period_years',
        'development_period_months',
        'development_start_date',
        'development_end_date'
    ];

    protected $casts = [
        'screenshots' => 'array',
        'app_types' => 'array',
        'genres' => 'array',
        'development_start_date' => 'date',
        'development_end_date' => 'date'
    ];

    /*
    |--------------------------------------------------------------------------
    | 公開ステータス関連
    |--------------------------------------------------------------------------
    */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_DRAFT => '下書き',
            self::STATUS_PUBLISHED => '公開'
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | アプリタイプ関連
    |--------------------------------------------------------------------------
    */
    public const TYPE_WEB = 'web_app';
    public const TYPE_IOS = 'ios_app';
    public const TYPE_ANDROID = 'android_app';
    public const TYPE_WINDOWS = 'windows_app';
    public const TYPE_MAC = 'mac_app';
    public const TYPE_LINUX = 'linux_app';
    public const TYPE_GAME = 'game';
    public const TYPE_OTHER = 'other';

    public static function getAppTypeOptions(): array
    {
        return config('app.app_types');
    }

    /*
    |--------------------------------------------------------------------------
    | アプリの状態関連
    |--------------------------------------------------------------------------
    */
    public const APP_STATUS_COMPLETED = 'completed';
    public const APP_STATUS_IN_DEVELOPMENT = 'in_development';

    public static function getAppStatusOptions(): array
    {
        return config('app.app_status');
    }

    /*
    |--------------------------------------------------------------------------
    | バリデーションルール
    |--------------------------------------------------------------------------
    */
    public static function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'screenshots' => 'nullable|array',
            'screenshots.*' => 'image|max:5120',
            'demo_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'status' => 'required|in:' . implode(',', array_keys(self::getStatusOptions())),
            'app_types' => 'required|array',
            'app_types.*' => 'in:' . implode(',', array_keys(self::getAppTypeOptions())),
            'app_status' => 'required|in:' . implode(',', array_keys(self::getAppStatusOptions())),
            'development_period_years' => 'integer|min:0',
            'development_period_months' => 'integer|min:0|max:11',
        ];
    }

    /**
     * スクリーンショットのURLを取得
     */
    public function getScreenshotUrlAttribute()
    {
        if (!empty($this->screenshots) && is_array($this->screenshots)) {
            $firstScreenshot = $this->screenshots[0] ?? null;
            if (is_array($firstScreenshot) && isset($firstScreenshot['url'])) {
                return $firstScreenshot['url'];
            }
            if (is_string($firstScreenshot)) {
                return $firstScreenshot;
            }
        }
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    */
    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }
} 