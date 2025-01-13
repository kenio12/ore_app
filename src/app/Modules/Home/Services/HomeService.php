<?php

namespace App\Modules\Home\Services;

use App\Modules\App\Models\App;

class HomeService
{
    public function getLatestApps()
    {
        return App::with('user')
            ->latest()
            ->get();
    }
} 