<?php

namespace App\Modules\App\Controllers\Sections;

use App\Modules\App\Controllers\Base\SectionController;
use App\Modules\App\Models\App;
use Illuminate\Http\Request;

class B_DevelopmentStoryController extends SectionController
{
    public function edit(string $appId)
    {
        $app = App::findOrFail($appId);

        return view('App::Forms.02_DevelopmentStoryForm', [
            'app' => $app,
            'currentSection' => 'development-story'
        ]);
    }

    public function update(Request $request, string $appId)
    {
        try {
            $app = App::findOrFail($appId);
            
            // バリデーションを実行
            $validatedData = $request->validate([
                'motivation' => 'nullable|string|max:10000',
                'challenges' => 'nullable|string|max:10000',
                'devised_points' => 'nullable|string|max:10000',
                'learnings' => 'nullable|string|max:10000',
                'future_plans' => 'nullable|string|max:10000',
                'overall_thoughts' => 'nullable|string|max:10000',
            ]);
            
            // データを更新
            $app->update($validatedData);
            
            // セクション完了をマーク
            $this->completeSection($appId, 'development-story');
            
            return redirect()
                ->route('apps.sections.development-story.edit', $appId)
                ->with('status', '開発ストーリーを更新しました！');
        } catch (\Exception $e) {
            throw $e;
        }
    }
} 