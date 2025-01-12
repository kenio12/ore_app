<?php

namespace App\Modules\Home\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\App\Models\App;

class HomeController extends Controller
{
    public function index()
    {
        return view('Home::home', [
            'apps' => App::with('user')->latest()->get(),
            'appTypeColors' => config('app-types.colors'),
            'appTypeLabels' => config('app-types.labels'),
            'statusLabels' => config('app-status.labels')
        ]);
    }
} 