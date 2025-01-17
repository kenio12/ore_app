<?php

namespace App\Modules\App\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Modules\App\Services\AppProgressManager;
use Illuminate\Http\Request;

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
        $currentSection = $this->getCurrentSection();
        $nextSection = $this->progressManager->getNextSection($currentSection);
        
        if (!$nextSection) {
            return redirect()->route('apps.show', $appId)
                ->with('success', 'すべてのセクションが完了しました！');
        }

        return redirect()->route("app.sections.{$nextSection}.edit", $appId);
    }

    /**
     * 現在のセクション名を取得
     */
    protected function getCurrentSection(): string
    {
        return $this->progressManager->getCurrentSection();
    }

    protected function completeSection(string $appId, string $section)
    {
        $this->progressManager->markSectionComplete($appId, $section);
    }
} 