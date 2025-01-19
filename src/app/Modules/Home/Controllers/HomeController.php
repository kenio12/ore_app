<?php

namespace App\Modules\Home\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\App\Models\App;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $apps = App::with('user')
                ->where('status', 'published')
                ->latest()
                ->get();

            return view('Home::home', [
                'apps' => $apps,
                'appTypeLabels' => config('app-module.constants.app_types'),
                'statusLabels' => config('app-module.constants.status')
            ]);

        } catch (\Exception $e) {
            \Log::error('Home page error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('Home::home', [
                'apps' => collect([]),
                'appTypeLabels' => config('app-module.constants.app_types'),
                'statusLabels' => config('app-module.constants.status')
            ])->with('error', 'アプリの読み込み中にエラーが発生しました');
        }
    }
} 