<?php

namespace App\Modules\AppV2\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\AppV2\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppController extends Controller
{
    public function index()
    {
        $apps = App::where('user_id', auth()->id())
                  ->latest()
                  ->paginate(12);

        return view('AppV2::index', compact('apps'));
    }

    public function create()
    {
        return view('AppV2::app-form');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $app = App::create([
                'user_id' => auth()->id(),
                'title' => $request->title,
                'description' => $request->description,
                'status' => 'draft',
            ]);

            $this->saveRelatedData($app, $request);

            DB::commit();
            return redirect()->route('apps-v2.show', $app)
                           ->with('success', 'アプリ情報を保存しました');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('App保存エラー', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()
                        ->withErrors(['error' => '保存中にエラーが発生しました']);
        }
    }

    public function show(App $app)
    {
        $this->authorize('view', $app);
        return view('AppV2::show', compact('app'));
    }

    public function edit(App $app)
    {
        $this->authorize('update', $app);
        return view('AppV2::app-form', [
            'app' => $app,
            'sections' => $this->getSections(),
        ]);
    }

    public function autosave(Request $request, $app = null)
    {
        try {
            Log::info('Autosave request received', [
                'app_id' => $app,
                'data' => $request->all()
            ]);

            // バリデーションを緩めに設定
            $validated = $request->validate([
                'basic' => 'array|nullable',
                'basic.title' => 'string|nullable',
                'basic.description' => 'string|nullable',
                'basic.types' => 'array|nullable',
                'basic.genres' => 'array|nullable',
                'screenshots' => 'array|nullable',
                'story' => 'array|nullable',
                'hardware' => 'array|nullable',
                'dev_env' => 'array|nullable',
                'architecture' => 'array|nullable',
                'frontend' => 'array|nullable',
                'backend' => 'array|nullable',
                'database' => 'array|nullable',
                'security' => 'array|nullable',
            ]);

            // セッションに保存
            $request->session()->put('app_form_data', $request->all());

            return response()->json([
                'success' => true,
                'message' => '自動保存しました'
            ]);

        } catch (\Exception $e) {
            Log::error('Autosave error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'エラーが発生しました: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getSections()
    {
        return [
            'basic' => ['title' => '基本情報', 'view' => '_01_basic-tab'],
            'screenshots' => ['title' => 'スクリーンショット', 'view' => '_02_screenshots-tab'],
            'story' => ['title' => '開発ストーリー', 'view' => '_03_story-tab'],
            'hardware' => ['title' => 'ハードウェア', 'view' => '_04_hardware-tab'],
            'dev_env' => ['title' => '開発環境', 'view' => '_05_dev-env-tab'],
            'architecture' => ['title' => 'アーキテクチャ', 'view' => '_06_architecture-tab'],
            'frontend' => ['title' => 'フロントエンド', 'view' => '_07_frontend-tab'],
            'backend' => ['title' => 'バックエンド', 'view' => '_08_backend-tab'],
            'database' => ['title' => 'データベース', 'view' => '_09_database-tab'],
            'security' => ['title' => 'セキュリティ', 'view' => '_10_security-tab'],
        ];
    }

    private function saveRelatedData(App $app, Request $request)
    {
        // 各セクションのデータを保存
        // 実装は後ほど詳細を詰めます
    }
} 