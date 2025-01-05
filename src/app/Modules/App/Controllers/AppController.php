<?php

namespace App\Modules\App\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\App\Models\App;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function create()
    {
        $app = new App();
        return view('App::create', ['app' => $app]);
    }

    public function store(Request $request)
    {
        // とりあえずリダイレクトだけ
        return redirect()->route('apps.create')
            ->with('success', '保存しました（仮）');
    }

    public function edit()
    {
        // 編集時も同様に初期化（後で実データに置き換え）
        $app = new \stdClass();
        return view('App::edit', ['app' => $app]);
    }

    public function update(Request $request)
    {
        // とりあえずリダイレクトだけ
        return back()->with('success', '更新しました（仮）');
    }

    public function index()
    {
        return view('App::index');
    }

    public function show($id)
    {
        $app = new \stdClass(); // 後で実データに置き換え
        return view('App::show', compact('app'));
    }
} 