<?php

namespace App\Modules\App\Services;

use App\Modules\App\Models\App;
use Illuminate\Support\Facades\Log;

class AppProgressManager
{
    private array $sections;

    public function __construct()
    {
        // セクション情報を初期化
        $this->sections = [
            'basic-info' => [
                'title' => '基本情報',
                'required' => true,
                'order' => 1
            ],
            'development-story' => [
                'title' => '開発ストーリー',
                'required' => true,
                'order' => 2
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
    }

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
            
            // 一貫した構造で保存
            $progress[$section] = [
                'completed' => true,
                'completed_at' => now()->toDateTimeString()
            ];
            
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
        try {
            $app = App::findOrFail($appId);
            $progress = $app->progress ?? [];
            
            return isset($progress[$section]['completed']) && 
                   $progress[$section]['completed'] === true;
        } catch (\Exception $e) {
            Log::error('Failed to check section completion', [
                'app_id' => $appId,
                'section' => $section,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * 全セクションの進捗状況を取得
     */
    public function getSectionProgress(string $appId): array
    {
        try {
            // 開始時のメモリ使用量を記録
            $initialMemory = memory_get_usage(true);
            Log::debug('開始時のメモリ使用量:', ['memory' => $initialMemory]);

            $app = App::select('id', 'progress')->findOrFail($appId);
            Log::debug('App取得後のメモリ:', [
                'memory' => memory_get_usage(true),
                'diff' => memory_get_usage(true) - $initialMemory
            ]);

            $progress = $app->progress ?? [];
            Log::debug('progress取得後のメモリ:', [
                'memory' => memory_get_usage(true),
                'diff' => memory_get_usage(true) - $initialMemory,
                'progress_size' => strlen(json_encode($progress))
            ]);
            
            $result = [];
            foreach ($this->sections as $key => $section) {
                $result[$key] = [
                    'completed' => isset($progress[$key]['completed']) && 
                                 $progress[$key]['completed'] === true,
                    'completed_at' => $progress[$key]['completed_at'] ?? null,
                    'title' => $section['title'],
                    'required' => $section['required'] ?? false
                ];

                // 10セクションごとにメモリ使用量をチェック
                if (count($result) % 10 === 0) {
                    Log::debug($key . 'セクション処理後のメモリ:', [
                        'memory' => memory_get_usage(true),
                        'diff' => memory_get_usage(true) - $initialMemory,
                        'sections_processed' => count($result)
                    ]);
                }
            }

            Log::debug('全処理完了時のメモリ:', [
                'memory' => memory_get_usage(true),
                'diff' => memory_get_usage(true) - $initialMemory,
                'result_size' => strlen(json_encode($result))
            ]);
            
            return $result;
        } catch (\Exception $e) {
            Log::error('メモリ使用量トラッキング中にエラー:', [
                'app_id' => $appId,
                'error' => $e->getMessage(),
                'memory' => memory_get_usage(true),
                'peak_memory' => memory_get_peak_usage(true)
            ]);
            return [];
        }
    }

    /**
     * 現在のセクション名を取得
     */
    public function getCurrentSection(): string
    {
        try {
            // メモリ使用量をログ
            Log::debug('Current memory usage:', [
                'memory' => memory_get_usage(true),
                'view_path' => view()->getEngine()->getFinder()->find(request()->path())
            ]);

            $path = request()->path();
            $segments = explode('/', $path);
            
            // sections/[セクション名]/[ID] の形式から[セクション名]を取得
            $sectionIndex = array_search('sections', $segments);
            if ($sectionIndex !== false && isset($segments[$sectionIndex + 1])) {
                return $segments[$sectionIndex + 1];
            }
            
            return 'basic-info';
        } catch (\Exception $e) {
            Log::error('View resolution error:', [
                'error' => $e->getMessage(),
                'memory' => memory_get_usage(true)
            ]);
            return 'basic-info';
        }
    }
}
    