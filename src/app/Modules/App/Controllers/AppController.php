<?php

namespace App\Modules\App\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\App\Models\App;

class AppController extends Controller
{
    public function index()
    {
        $apps = App::with('user')->latest()->get();
        return view('App::list', compact('apps'));
    }

    // 他のCRUDメソッドは必要に応じて追加
} 