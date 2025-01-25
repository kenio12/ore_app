<?php

namespace App\Modules\AppV2\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\AppV2\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AppController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $apps = App::where('user_id', auth()->id())
                  ->latest()
                  ->paginate(12);

        return view('AppV2::index', compact('apps'));
    }

    public function create()
    {
        // セッションから一時データを削除
        session()->forget('app_form_data');
        
        return view('AppV2::app-form', [
            'sections' => $this->getSections(),
            // 初期状態を明示的に設定
            'app' => null
        ]);
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

    public function show($id)
    {
        $app = App::with(['screenshots'])->findOrFail($id);
        
        // スクリーンショットデータの整形
        $app->screenshots = $app->screenshots->map(function($screenshot) {
            return [
                'public_id' => $screenshot->cloudinary_public_id,
                'url' => $screenshot->url
            ];
        });

        return view('app-v2.show', compact('app'));
    }

    public function edit(App $app)
    {
        $this->authorize('update', $app);

        // デバッグ用のログを追加
        Log::info('Edit app data:', ['app' => $app->toArray()]);

        return view('AppV2::app-form', [
            'app' => $app,
            'sections' => $this->getSections(),
            'initialData' => json_encode([  // 初期データをJSON形式で渡す
                'basic' => [
                    'title' => $app->title,
                    'description' => $app->description,
                    'status' => $app->status,
                    // 他の必要なデータ
                ],
                // 他のセクションのデータ
            ])
        ]);
    }

    public function autosave(Request $request, $appId = null)
    {
        try {
            Log::info('Autosave request:', [
                'appId' => $appId,
                'formData' => $request->input('formData')
            ]);

            $formData = $request->input('formData');
            $userId = auth()->id();

            if ($appId) {
                $app = App::findOrFail($appId);
                // 更新処理
                $app->update([
                    'title' => $formData['basic']['title'] ?? $app->title,
                    'description' => $formData['basic']['description'] ?? $app->description,
                    'status' => $formData['basic']['status'] ?? $app->status,
                    // 他の必要なフィールド
                ]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Autosave error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '保存に失敗しました'
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