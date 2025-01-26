<?php

namespace App\Modules\AppV2\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\AppV2\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

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
        
        // 一時的なアプリレコードを作成
        $app = App::create([
            'user_id' => auth()->id(),
            'title' => '無題のアプリ',
            'status' => 'draft'
        ]);

        // 編集画面にリダイレクト
        return redirect()->route('apps-v2.edit', ['app' => $app->id]);
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

        try {
            // スクリーンショットデータの取得と整形
            $screenshots = $app->screenshots()
                ->orderBy('order')
                ->get()
                ->map(function($screenshot) {
                    return [
                        'public_id' => $screenshot->cloudinary_public_id,
                        'url' => $screenshot->url
                    ];
                });

            // デバッグログを追加して、値を確認
            Log::debug('Date values:', [
                'raw_start' => $app->development_start_date,
                'raw_end' => $app->development_end_date
            ]);

            // 初期データの準備
            $initialData = [
                'basic' => [
                    'title' => $app->title,
                    'description' => $app->description,
                    'types' => $app->app_types ?? [],
                    'genres' => $app->genres ?? [],
                    'app_status' => $app->app_status,
                    'status' => $app->status,
                    'demo_url' => $app->demo_url,
                    'github_url' => $app->github_url,
                    'development_start_date' => $app->development_start_date ? $app->development_start_date->format('Y-m-d') : null,
                    'development_end_date' => $app->development_end_date ? $app->development_end_date->format('Y-m-d') : null,
                    'development_period_years' => $app->development_period_years ?? 0,
                    'development_period_months' => $app->development_period_months ?? 0,
                    'motivation' => $app->motivation,
                    'purpose' => $app->purpose
                ],
                'screenshots' => $screenshots,
                'story' => json_decode($app->data, true)['story'] ?? [],
                'hardware' => json_decode($app->hardware_info, true) ?? [],
                'dev_env' => json_decode($app->dev_env_info, true) ?? [],
                'architecture' => json_decode($app->architecture_info, true) ?? [],
                'frontend' => json_decode($app->frontend_info, true) ?? [],
                'backend' => json_decode($app->backend_info, true) ?? [],
                'database' => json_decode($app->database_info, true) ?? [],
                'security' => json_decode($app->security_info, true) ?? []
            ];

            // 初期データの日付をログで確認
            Log::debug('Formatted dates:', [
                'start' => $initialData['basic']['development_start_date'],
                'end' => $initialData['basic']['development_end_date']
            ]);

            return view('AppV2::app-form', [
                'app' => $app,
                'sections' => $this->getSections(),
                'initialData' => $initialData
            ]);

        } catch (\Exception $e) {
            Log::error('Edit page error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => '編集ページの読み込み中にエラーが発生しました']);
        }
    }

    public function autosave(Request $request, $id)
    {
        try {
            // リクエストの詳細をログ
            Log::debug('Autosave request details:', [
                'id' => $id,
                'route' => $request->route()->getName(),
                'parameters' => $request->route()->parameters(),
                'formData' => $request->input('formData')
            ]);

            $formData = $request->input('formData');
            $app = App::findOrFail($id);

            // デバッグログ追加
            Log::debug('Autosave dates:', [
                'start' => $formData['basic']['development_start_date'],
                'end' => $formData['basic']['development_end_date']
            ]);

            // 日付データの処理を明示的に行う
            $startDate = !empty($formData['basic']['development_start_date']) 
                ? Carbon::parse($formData['basic']['development_start_date']) 
                : null;
            $endDate = !empty($formData['basic']['development_end_date']) 
                ? Carbon::parse($formData['basic']['development_end_date']) 
                : null;

            // 基本データの更新
            $app->update([
                'title' => $formData['basic']['title'] ?? null,
                'description' => $formData['basic']['description'] ?? null,
                'status' => $formData['basic']['status'] ?? 'draft',
                'app_status' => $formData['basic']['app_status'] ?? null,
                'demo_url' => $formData['basic']['demo_url'] ?? null,
                'github_url' => $formData['basic']['github_url'] ?? null,
                'development_start_date' => $startDate,
                'development_end_date' => $endDate,
                'development_period_years' => $formData['basic']['development_period_years'] ?? 0,
                'development_period_months' => $formData['basic']['development_period_months'] ?? 0,
                'motivation' => $formData['basic']['motivation'] ?? null,
                'purpose' => $formData['basic']['purpose'] ?? null,
                'app_types' => $formData['basic']['types'] ?? [],
                'genres' => $formData['basic']['genres'] ?? [],
            ]);

            // スクリーンショットの保存
            if (isset($formData['screenshots'])) {
                $this->saveScreenshots($app, $formData['screenshots']);
            }

            // その他のセクションデータをJSON形式で保存
            $app->update([
                'hardware_info' => json_encode($formData['hardware'] ?? []),
                'dev_env_info' => json_encode($formData['dev_env'] ?? []),
                'architecture_info' => json_encode($formData['architecture'] ?? []),
                'security_info' => json_encode($formData['security'] ?? []),
                'frontend_info' => json_encode($formData['frontend'] ?? []),
                'backend_info' => json_encode($formData['backend'] ?? []),
                'database_info' => json_encode($formData['database'] ?? []),
                'data' => json_encode([
                    'story' => $formData['story'] ?? [],
                    // その他の追加データ
                ])
            ]);

            return response()->json([
                'success' => true,
                'message' => '保存しました'
            ]);
        } catch (\Exception $e) {
            Log::error('Autosave error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '保存に失敗しました: ' . $e->getMessage()
            ], 500);
        }
    }

    private function saveScreenshots($app, $screenshots)
    {
        // 既存のスクリーンショットを一旦削除
        $app->screenshots()->delete();
        
        // 新しいスクリーンショットを保存
        foreach ($screenshots as $index => $screenshot) {
            $app->screenshots()->create([
                'cloudinary_public_id' => $screenshot['public_id'],
                'url' => $screenshot['url'],
                'order' => $index
            ]);
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