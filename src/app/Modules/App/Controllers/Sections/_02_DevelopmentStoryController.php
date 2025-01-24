<?php

namespace App\Modules\App\Controllers\Sections;

use App\Modules\App\Controllers\Base\SectionController;
use App\Modules\App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class _02_DevelopmentStoryController extends SectionController
{
    public function edit(string $appId)
    {
        $app = App::findOrFail($appId);
        
        $sections = $this->progressManager->getSections();
        $currentSection = 'development-story';

        return view('App::app-form', [
            'app' => $app,
            'currentSection' => $currentSection,
            'sectionTitle' => $sections[$currentSection]['title'],
            'sections' => $sections,
            'previousSection' => $this->progressManager->getPreviousSection($currentSection),
            'nextSection' => $this->progressManager->getNextSection($currentSection)
        ]);
    }

    public function update(Request $request, string $appId)
    {
        try {
            Log::info('開発ストーリー更新開始', [
                'appId' => $appId,
                'request_all' => $request->all()
            ]);

            $app = App::findOrFail($appId);
            
            $validatedData = $request->validate([
                'motivation' => 'nullable|string|max:10000',
                'challenges' => 'nullable|string|max:10000',
                'devised_points' => 'nullable|string|max:10000',
                'learnings' => 'nullable|string|max:10000',
                'future_plans' => 'nullable|string|max:10000',
                'overall_thoughts' => 'nullable|string|max:10000',
                'direction' => 'required|in:prev,next',
            ]);
            
            Log::info('バリデーション通過', [
                'validatedData' => $validatedData
            ]);

            // フォームデータのみを更新用に抽出
            $formData = collect($validatedData)
                ->except('direction')
                ->toArray();
            
            Log::info('更新前のデータ', [
                'app_before' => $app->toArray(),
                'formData' => $formData
            ]);

            // データを保存
            $app->update($formData);
            
            Log::info('更新後のデータ', [
                'app_after' => $app->fresh()->toArray()
            ]);
            
            // セクション完了を記録
            $this->completeSection($appId, 'development-story');
            
            // 移動方向に応じてリダイレクト
            if ($validatedData['direction'] === 'prev') {
                return $this->previous($appId);
            } else {
                return $this->next($appId);
            }
            
        } catch (\Exception $e) {
            Log::error('開発ストーリー更新エラー', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => '保存中にエラーが発生しました。']);
        }
    }
} 