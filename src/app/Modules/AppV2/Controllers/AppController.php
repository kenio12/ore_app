<?php

namespace App\Modules\AppV2\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\AppV2\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;
use App\Modules\AppV2\Models\Screenshot;

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
            // より詳細なデバッグ情報を追加
            Log::debug('App Model Details:', [
                'app_id' => $app->id,
                'user_id' => $app->user_id,
                'table_name' => $app->getTable(),
                'connection' => DB::connection()->getDatabaseName()
            ]);

            // スクリーンショットのクエリを実行前に確認
            $query = $app->screenshots()->toSql();
            $bindings = $app->screenshots()->getBindings();
            Log::debug('Screenshot Query:', [
                'sql' => $query,
                'bindings' => $bindings
            ]);

            // ==================== データベースチェック関連 ====================
            $screenshotsCount = DB::table('screenshots')
                ->where('app_id', $app->id)
                ->count();

            Log::debug('Direct DB Check:', [
                'screenshots_count' => $screenshotsCount,
                'app_id' => $app->id
            ]);

            // ==================== スクリーンショット関連 ====================
            // スクリーンショットの取得と整形
            $screenshots = $app->screenshots()
                ->orderBy('order')
                ->get();

            Log::debug('Raw Screenshots:', [
                'count' => $screenshots->count(),
                'data' => $screenshots->toArray()
            ]);

            $screenshots = $screenshots->map(function($screenshot) {
                return [
                    'id' => $screenshot->id,
                    'public_id' => $screenshot->cloudinary_public_id,
                    'url' => $screenshot->url
                ];
            });

            Log::debug('Processed Screenshots:', [
                'data' => $screenshots->toArray()
            ]);

            // ==================== ユーザー情報関連 ====================
            Log::debug('App and User info:', [
                'app_id' => $app->id,
                'user_id' => $app->user_id,
                'user_name' => $app->user->name
            ]);

            // ==================== 日付関連 ====================
            Log::debug('Date values:', [
                'raw_start' => $app->development_start_date,
                'raw_end' => $app->development_end_date
            ]);

            // ==================== 初期データ準備（タブごと） ====================
            $initialData = [
                // ==================== 01_basic-tab ====================
                'basic' => [
                    // 基本情報
                    'title' => $app->title,
                    'description' => $app->description,
                    'user_name' => $app->user->name,
                    
                    // アプリ分類
                    'types' => $app->app_types ?? [],
                    'genres' => $app->genres ?? [],
                    'app_status' => $app->app_status,
                    
                    // URL情報
                    'demo_url' => $app->demo_url,
                    'github_url' => $app->github_url,
                    
                    // 開発期間
                    'development_start_date' => $app->development_start_date,
                    'development_end_date' => $app->development_end_date,
                    'development_period_years' => $app->development_period_years,
                    'development_period_months' => $app->development_period_months,
                    
                    // 目的・動機
                    'motivation' => $app->motivation,
                    'purpose' => $app->purpose
                ],

                // ==================== 02_screenshots-tab ====================
                'screenshots' => $screenshots,

                // ==================== 03_story-tab ====================
                'story' => [
                    'development_success' => $app->development_success,
                    'development_challenge' => $app->development_challenge,
                    'development_fun' => $app->development_fun,
                    'development_learning' => $app->development_learning,
                    'development_message' => $app->development_message
                ],

                // ==================== 04_hardware-tab ====================
                'hardware' => json_decode($app->hardware_info, true) ?? [],

                // ==================== 05_dev-env-tab ====================
                'dev_env' => json_decode($app->dev_env_info, true) ?? [],

                // ==================== 06_architecture-tab ====================
                'architecture' => json_decode($app->architecture_info, true) ?? [],

                // ==================== 07_frontend-tab ====================
                'frontend' => json_decode($app->frontend_info, true) ?? [],

                // ==================== 08_backend-tab ====================
                'backend' => json_decode($app->backend_info, true) ?? [],

                // ==================== 09_database-tab ====================
                'database' => json_decode($app->database_info, true) ?? [],

                // ==================== 10_security-tab ====================
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

    public function autosave(Request $request, App $app)
    {
        Log::debug('Autosave request data:', $request->all());

        try {
            DB::beginTransaction();

            // ストーリー情報の保存
            if ($request->has('story')) {
                $app->update([
                    'development_trigger' => $request->input('story.development_trigger'),
                    'development_hardship' => $request->input('story.development_hardship'),
                    'development_tearful' => $request->input('story.development_tearful'),
                    'development_enjoyable' => $request->input('story.development_enjoyable'),
                    'development_funny' => $request->input('story.development_funny'),
                    'development_impression' => $request->input('story.development_impression'),
                    'development_oneword' => $request->input('story.development_oneword')
                ]);
            }

            // ==================== ⚠️危険！以下のコードは絶対に消すな！！！！ ====================
            // 消したら殺す！！！！
            // スクリーンショット機能が完全に壊れる！！！！

            // スクリーンショットの保存処理
            if ($request->has('screenshots')) {
                Log::debug('Processing screenshots:', [
                    'screenshots' => $request->input('screenshots'),
                    'app_id' => $app->id
                ]);
                
                // 既存のスクリーンショットを更新
                foreach ($request->input('screenshots') as $screenshot) {
                    Screenshot::updateOrCreate(
                        ['id' => $screenshot['id']],
                        [
                            'app_id' => $app->id,
                            'public_id' => $screenshot['public_id'],
                            'url' => $screenshot['url'],
                            'order' => $screenshot['order']
                        ]
                    );
                }
            }

            // ==================== ⚠️危険！上のコードは絶対に消すな！！！！ ====================

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Autosave error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'オートセーブに失敗しました'
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