<?php

namespace App\Modules\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class App extends Model
{
    use HasFactory, SoftDeletes;

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
        return [
            self::TYPE_WEB => 'Webアプリケーション',
            self::TYPE_IOS => 'iOSアプリ',
            self::TYPE_ANDROID => 'Androidアプリ',
            self::TYPE_WINDOWS => 'Windowsアプリ',
            self::TYPE_MAC => 'macOSアプリ',
            self::TYPE_LINUX => 'Linuxアプリ',
            self::TYPE_GAME => 'ゲーム',
            self::TYPE_OTHER => 'その他'
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | 開発状態関連
    |--------------------------------------------------------------------------
    */
    public const APP_STATUS_COMPLETED = 'completed';
    public const APP_STATUS_IN_DEVELOPMENT = 'in_development';

    public static function getAppStatusOptions(): array
    {
        return [
            self::APP_STATUS_COMPLETED => '完成',
            self::APP_STATUS_IN_DEVELOPMENT => '開発中'
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | 開発環境関連
    |--------------------------------------------------------------------------
    */
    // エディタ
    public const EDITOR_VSCODE = 'vscode';
    public const EDITOR_PHPSTORM = 'phpstorm';
    public const EDITOR_SUBLIME = 'sublime';
    public const EDITOR_VIM = 'vim';
    public const EDITOR_OTHER = 'other';

    public static function getEditorOptions(): array
    {
        return [
            self::EDITOR_VSCODE => 'Visual Studio Code',
            self::EDITOR_PHPSTORM => 'PhpStorm',
            self::EDITOR_SUBLIME => 'Sublime Text',
            self::EDITOR_VIM => 'Vim',
            self::EDITOR_OTHER => 'その他'
        ];
    }

    // バージョン管理
    public const VCS_GIT = 'git';
    public const VCS_SVN = 'svn';
    public const VCS_OTHER = 'other';

    public static function getVersionControlOptions(): array
    {
        return [
            self::VCS_GIT => 'Git',
            self::VCS_SVN => 'SVN',
            self::VCS_OTHER => 'その他'
        ];
    }

    // 仮想化
    public const VIRT_DOCKER = 'docker';
    public const VIRT_VAGRANT = 'vagrant';
    public const VIRT_VM = 'vm';
    public const VIRT_OTHER = 'other';

    public static function getVirtualizationOptions(): array
    {
        return [
            self::VIRT_DOCKER => 'Docker',
            self::VIRT_VAGRANT => 'Vagrant',
            self::VIRT_VM => '仮想マシン',
            self::VIRT_OTHER => 'その他'
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | デフォルト値
    |--------------------------------------------------------------------------
    */
    protected $attributes = [
        'status' => self::STATUS_DRAFT,
        'development_period_years' => 0,
        'development_period_months' => 0,
    ];

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
            'app_type' => 'required|in:' . implode(',', array_keys(self::getAppTypeOptions())),
            'app_status' => 'required|in:' . implode(',', array_keys(self::getAppStatusOptions())),
            'development_period_years' => 'integer|min:0',
            'development_period_months' => 'integer|min:0|max:11',
            'editors' => 'nullable|array',
            'editors.*' => 'in:' . implode(',', array_keys(self::getEditorOptions())),
            'version_control' => 'nullable|array',
            'version_control.*' => 'in:' . implode(',', array_keys(self::getVersionControlOptions())),
            'virtualization' => 'nullable|array',
            'virtualization.*' => 'in:' . implode(',', array_keys(self::getVirtualizationOptions())),
        ];
    }

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
        'development_start_date',
        'development_end_date',
        'app_type',
        'app_status',
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

    /**
     * スクリーンショットのURLを取得
     */
    public function getScreenshotUrlAttribute()
    {
        if (!empty($this->screenshots) && is_array($this->screenshots)) {
            // 最初のスクリーンショットのURLを文字列として返す
            $firstScreenshot = $this->screenshots[0] ?? null;
            return is_array($firstScreenshot) ? $firstScreenshot['url'] : $firstScreenshot;
        }
        return null;
    }
} 