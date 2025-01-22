<?php

namespace App\Modules\App\Controllers\Sections;

use App\Modules\App\Controllers\Base\SectionController;
use App\Modules\App\Models\App;
use Illuminate\Http\Request;

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
            $app = App::findOrFail($appId);
            
            $validatedData = $request->validate([
                'motivation' => 'nullable|string|max:10000',
                'challenges' => 'nullable|string|max:10000',
                'devised_points' => 'nullable|string|max:10000',
                'learnings' => 'nullable|string|max:10000',
                'future_plans' => 'nullable|string|max:10000',
                'overall_thoughts' => 'nullable|string|max:10000',
            ]);
            
            $app->update($validatedData);
            
            $this->completeSection($appId, 'development-story');
            
            return $this->next($appId);
        } catch (\Exception $e) {
            throw $e;
        }
    }
} 