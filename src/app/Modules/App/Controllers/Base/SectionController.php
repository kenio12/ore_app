<?php

namespace App\Modules\App\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Modules\App\Services\AppProgressManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

abstract class SectionController extends Controller
{
    protected ?AppProgressManager $progressManager;

    public function __construct()
    {
        // コンストラクタでProgressManagerを初期化
        $this->progressManager = app(AppProgressManager::class);
    }

    abstract public function edit(string $appId);
    abstract public function update(Request $request, string $appId);

    /**
     * 次のセクションへ遷移
     */
    public function next(string $appId)
    {
        try {
            $currentSection = $this->getCurrentSection();
            $nextSection = $this->progressManager->getNextSection($currentSection);
            
            if (!$nextSection) {
                $this->progressManager = null; // インスタンスを解放
                return redirect()->route('apps.show', $appId)
                    ->with('success', 'すべてのセクションが完了しました！');
            }

            $route = "app.sections.{$nextSection}.edit";
            
            // 使い終わったら解放
            $this->progressManager = null;
            
            return redirect()->route($route, $appId);
        } catch (\Exception $e) {
            Log::error('Next section error:', [
                'message' => $e->getMessage(),
                'section' => $currentSection ?? 'unknown'
            ]);
            
            // エラー時にも確実に解放
            $this->progressManager = null;
            throw $e;
        }
    }

    /**
     * 前のセクションへ遷移
     */
    public function previous(string $appId)
    {
        try {
            $currentSection = $this->getCurrentSection();
            $previousSection = $this->progressManager->getPreviousSection($currentSection);
            
            if (!$previousSection) {
                $this->progressManager = null;
                return redirect()->route('apps.index')
                    ->with('info', '最初のセクションです');
            }

            $route = "app.sections.{$previousSection}.edit";
            
            // 使い終わったら解放
            $this->progressManager = null;
            
            return redirect()->route($route, $appId);
        } catch (\Exception $e) {
            Log::error('Previous section error:', [
                'message' => $e->getMessage(),
                'section' => $currentSection ?? 'unknown'
            ]);
            
            // エラー時にも確実に解放
            $this->progressManager = null;
            throw $e;
        }
    }

    /**
     * 現在のセクション名を取得
     */
    protected function getCurrentSection(): string
    {
        try {
            if (!$this->progressManager) {
                // もし何らかの理由でnullになってたら再初期化
                $this->progressManager = app(AppProgressManager::class);
            }

            $sections = $this->progressManager->getSections();
            $currentSection = $this->progressManager->getCurrentSection();
            
            if (!isset($sections[$currentSection])) {
                Log::error('Invalid section:', [
                    'current' => $currentSection,
                    'sections' => array_keys($sections)
                ]);
                
                $this->progressManager = null;
                throw new \Exception('Invalid section');
            }

            return $currentSection;
        } catch (\Exception $e) {
            Log::error('Get current section error:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function completeSection(string $appId, string $section)
    {
        try {
            $this->progressManager->markSectionComplete($appId, $section);
            $this->progressManager = null; // これでエラーにならへん
        } catch (\Exception $e) {
            Log::error('Complete section error:', [
                'error' => $e->getMessage(),
                'appId' => $appId,
                'section' => $section
            ]);
            $this->progressManager = null;
            throw $e;
        }
    }

    /**
     * セクションの移動処理
     */
    protected function handleNavigation(string $appId, string $direction)
    {
        if ($direction === 'prev') {
            return $this->previous($appId);
        }
        return $this->next($appId);
    }
} 