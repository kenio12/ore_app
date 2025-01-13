<?php

namespace App\Modules\App\Services;

use App\Modules\App\Models\App;
use Illuminate\Support\Facades\Log;

class AppProgressManager
{
    private array $sections = [
        'basic-info' => [
            'title' => '基本情報',
            'required' => true,
        ],
        'development-story' => [
            'title' => '開発ストーリー',
            'required' => false,
        ],
        'hardware' => [
            'title' => 'ハードウェア環境',
            'required' => false,
        ],
        'dev-tools' => [
            'title' => '開発ツール環境',
            'required' => false,
        ],
        'architecture' => [
            'title' => 'アーキテクチャ',
            'required' => false,
        ],
        'security' => [
            'title' => 'セキュリティと品質管理',
            'required' => false,
        ],
        'backend' => [
            'title' => 'バックエンド環境',
            'required' => false,
        ],
        'frontend' => [
            'title' => 'フロントエンド環境',
            'required' => false,
        ],
        'database' => [
            'title' => 'データベース環境',
            'required' => false,
        ]
    ];

    public function getSections(): array
    {
        return $this->sections;
    }

    public function getNextSection(string $currentSection): ?string
    {
        $sections = array_keys($this->sections);
        $currentIndex = array_search($currentSection, $sections);
        return isset($sections[$currentIndex + 1]) ? $sections[$currentIndex + 1] : null;
    }

    public function getPreviousSection(string $currentSection): ?string
    {
        $sections = array_keys($this->sections);
        $currentIndex = array_search($currentSection, $sections);
        return ($currentIndex > 0) ? $sections[$currentIndex - 1] : null;
    }

    /**
     * セクションの完了をマークする
     */
    public function markSectionComplete(string $appId, string $section)
    {
        $app = App::findOrFail($appId);
        
        // 現在の進捗状況を取得
        $progress = $app->progress ?? [];
        
        // セクションを完了としてマーク
        $progress[$section] = [
            'completed' => true,
            'completed_at' => now()
        ];
        
        // 進捗状況を更新
        $app->progress = $progress;
        $app->save();

        Log::info('Section marked as complete', [
            'app_id' => $appId,
            'section' => $section,
            'progress' => $progress
        ]);
    }

    /**
     * セクションが完了しているかチェック
     */
    public function isSectionComplete(string $appId, string $section): bool
    {
        $app = App::findOrFail($appId);
        return isset($app->progress[$section]['completed']) && $app->progress[$section]['completed'];
    }

    /**
     * 全セクションの進捗状況を取得
     */
    public function getSectionProgress(string $appId): array
    {
        $app = App::findOrFail($appId);
        return $app->progress ?? [];
    }
} 