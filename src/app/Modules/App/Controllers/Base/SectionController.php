<?php

namespace App\Modules\App\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Modules\App\Services\AppProgressManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

abstract class SectionController extends Controller
{
    protected AppProgressManager $progressManager;

    public function __construct(AppProgressManager $progressManager)
    {
        $this->progressManager = $progressManager;
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
     * 現在のセクション名を取得
     */
    protected function getCurrentSection(): string
    {
        try {
            $sections = $this->progressManager->getSections();
            $currentSection = $this->progressManager->getCurrentSection();
            
            if (!isset($sections[$currentSection])) {
                Log::error('Invalid section:', [
                    'current' => $currentSection,
                    'sections' => array_keys($sections)
                ]);
                
                $this->progressManager = null; // インスタンスを解放
                throw new \Exception('Invalid section');
            }

            return $currentSection;
        } catch (\Exception $e) {
            // エラー時にも確実に解放
            $this->progressManager = null;
            Log::error('Get current section error:', [
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    protected function completeSection(string $appId, string $section)
    {
        try {
            $this->progressManager->markSectionComplete($appId, $section);
            $this->progressManager = null; // 使い終わったら解放
        } catch (\Exception $e) {
            Log::error('Complete section error:', [
                'error' => $e->getMessage(),
                'appId' => $appId,
                'section' => $section
            ]);
            $this->progressManager = null; // エラー時にも解放
            throw $e;
        }
    }
} 