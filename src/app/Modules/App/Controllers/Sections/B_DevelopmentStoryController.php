<?php

namespace App\Modules\App\Controllers\Sections;

use App\Modules\App\Controllers\Base\SectionController;
use App\Modules\App\Models\App;
use Illuminate\Foundation\Http\FormRequest;

class B_DevelopmentStoryController extends SectionController
{
    public function edit(string $appId)
    {
        $app = App::findOrFail($appId);
        
        return view('app::Forms.02_DevelopmentStoryForm', [
            'app' => $app,
            'currentSection' => 'development-story'
        ]);
    }

    public function update(FormRequest $request, string $appId)
    {
        try {
            $app = App::findOrFail($appId);
            
            // TODO: 開発ストーリーの更新処理を実装
            
            // セクション完了をマーク
            $this->completeSection($appId, 'development-story');
            
            return redirect()
                ->route('app.sections.next-section.edit', ['app' => $app->id])
                ->with('success', '開発ストーリーを保存しました！');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'データの保存に失敗しました: ' . $e->getMessage());
        }
    }
} 