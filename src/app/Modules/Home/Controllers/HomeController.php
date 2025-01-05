<?php

namespace App\Modules\Home\Controllers;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('Home::home', [
            'apps' => [],
            'appTypeColors' => config('app-types.colors'),
            'appTypeLabels' => config('app-types.labels'),
            'statusLabels' => config('app-status.labels')
        ]);
    }
} 