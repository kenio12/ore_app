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

    protected function completeSection(string $appId, string $section)
    {
        $this->progressManager->markSectionComplete($appId, $section);
    }
} 