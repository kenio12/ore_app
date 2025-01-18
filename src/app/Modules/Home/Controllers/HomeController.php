<?php

namespace App\Modules\Home\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\App\Models\App;

class HomeController extends Controller
{
    public function index()
    {
        $apps = App::with('user')
            ->select('apps.*')
            ->distinct()
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Home::home', [
            'apps' => $apps,
            'appTypeLabels' => [
                'web_app' => 'Webアプリケーション',
                'ios_app' => 'iOSアプリ',
                'android_app' => 'Androidアプリ',
                'windows_app' => 'Windowsアプリ',
                'mac_app' => 'macOSアプリ',
                'linux_app' => 'Linuxアプリ',
                'game' => 'ゲーム',
                'other' => 'その他'
            ],
            'statusLabels' => [
                'draft' => '下書き',
                'published' => '公開'
            ]
        ]);
    }
} 