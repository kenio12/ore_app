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
                ->select('apps.*')
                ->distinct()
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('Home::home', [
                'apps' => $apps,
                'appTypeLabels' => config('app.app_type_labels', []),
                'statusLabels' => config('app.status_labels', [])
            ]);

        } catch (\Exception $e) {
            \Log::error('Home page error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('Home::home', [
                'apps' => collect([]),
                'appTypeLabels' => config('app.app_type_labels', []),
                'statusLabels' => config('app.status_labels', [])
            ])->with('error', 'アプリの読み込み中にエラーが発生しました');
        }
    }
} 