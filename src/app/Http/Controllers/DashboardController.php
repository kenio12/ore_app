<?php

namespace App\Http\Controllers;

use App\Modules\AppV2\Models\App;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $apps = App::query()
            ->where('user_id', auth()->id())
            ->with('screenshots')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('dashboard', compact('apps'));
    }
} 