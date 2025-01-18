<?php

namespace App\Modules\App\Services;

use App\Modules\App\Models\App;
use Illuminate\Support\Facades\Log;

class AppProgressManager
{
    private array $sections = [
        'basic-info' => [
            'title' => '基本情報',
            'next' => 'development-story',
            'prev' => null,
            'required' => true,
        ],
        'development-story' => [
            'title' => '開発ストーリー',
            'next' => 'hardware',
            'prev' => 'basic-info',
            'required' => true,
        ],
        'hardware' => [
            'title' => 'ハードウェア',
            'next' => 'dev-tools',
            'prev' => 'development-story',
            'required' => false,
        ],
        'dev-tools' => [
            'title' => '開発ツール',
            'next' => 'architecture',
            'prev' => 'hardware',
            'required' => true,
        ],
        'architecture' => [
            'title' => 'アーキテクチャ',
            'next' => 'security',
            'prev' => 'dev-tools',
            'required' => true,
        ],
        'security' => [
            'title' => 'セキュリティ',
            'next' => 'backend',
            'prev' => 'architecture',
            'required' => true,
        ],
        'backend' => [
            'title' => 'バックエンド',
            'next' => 'frontend',
            'prev' => 'security',
            'required' => true,
        ],
        'frontend' => [
            'title' => 'フロントエンド',
            'next' => 'database',
            'prev' => 'backend',
            'required' => true,
        ],
        'database' => [
            'title' => 'データベース',
            'next' => null,
            'prev' => 'frontend',
            'required' => true,
        ]
    ];

    public function getSections(): array
    {
        return $this->sections;
    }

    public function getNextSection(string $currentSection): ?string
    {
        return $this->sections[$currentSection]['next'] ?? null;
    }

    public function getPreviousSection(string $currentSection): ?string
    {
        return $this->sections[$currentSection]['prev'] ?? null;
    }

    /**
     * セクションの完了をマークする
     */
    public function markSectionComplete(string $appId, string $section): void
    {
        try {
            $app = App::findOrFail($appId);
            $progress = $app->progress ?? [];
            $progress[$section] = true;
            $app->progress = $progress;
            $app->save();
        } catch (\Exception $e) {
            Log::error('Failed to mark section complete', [
                'app_id' => $appId,
                'section' => $section,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
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

    /**
     * 現在のセクション名を取得
     */
    public function getCurrentSection(): string
    {
        // URLから現在のセクション名を取得
        $path = request()->path();
        $segments = explode('/', $path);
        
        // sections/[セクション名]/[ID] の形式から[セクション名]を取得
        $sectionIndex = array_search('sections', $segments);
        if ($sectionIndex !== false && isset($segments[$sectionIndex + 1])) {
            return $segments[$sectionIndex + 1];
        }
        
        // デフォルトは basic-info
        return 'basic-info';
    }
}
    