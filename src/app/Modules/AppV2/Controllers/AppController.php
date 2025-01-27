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
        $app = App::with(['screenshots' => function($query) {
            $query->orderBy('order', 'asc');  // orderで昇順に並べ替え
        }])->findOrFail($id);
        
        // スクリーンショットデータの整形
        $app->screenshots = $app->screenshots->map(function($screenshot) {
            return [
                'id' => $screenshot->id,
                'public_id' => $screenshot->cloudinary_public_id,
                'url' => $screenshot->url,
                'order' => $screenshot->order
            ];
        });

        return view('AppV2::show', compact('app'));
    }

    public function edit(App $app)
    {
        // スクリーンショットを含めて取得
        $app->load(['screenshots' => function($query) {
            $query->orderBy('order', 'asc');
        }]);
        
        // 初期データを正しい形式で準備
        $initialData = [
            'screenshots' => $app->screenshots->map(function($screenshot) {
                return [
                    'id' => $screenshot->id,
                    'public_id' => $screenshot->cloudinary_public_id,
                    'url' => $screenshot->url,
                    'order' => $screenshot->order
                ];
            })->toArray()
        ];
        
        // デバッグ用
        Log::debug('Edit画面初期データ準備:', [
            'app_id' => $app->id,
            'screenshots' => $initialData['screenshots']
        ]);
        
        $sections = [
            'basic' => ['title' => '基本情報'],
            'screenshots' => ['title' => 'スクリーンショット'],
            'story' => ['title' => '開発ストーリー'],
            'hardware' => ['title' => 'ハードウェア'],
            'dev_env' => ['title' => '開発環境'],
            'architecture' => ['title' => 'アーキテクチャ'],
            'frontend' => ['title' => 'フロントエンド'],
            'backend' => ['title' => 'バックエンド'],
            'database' => ['title' => 'データベース'],
            'security' => ['title' => 'セキュリティ']
        ];

        return view('AppV2::app-form', [
            'app' => $app,
            'initialData' => $initialData,
            'sections' => $sections
        ]);
    }

    public function autosave(Request $request, App $app)
    {
        try {
            DB::beginTransaction();

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

            // 基本情報の保存処理を追加
            if ($request->has('formData.basic')) {
                $basicData = $request->input('formData.basic');
                
                // 配列データはJSONに変換
                $app->update([
                    'title' => $basicData['title'],
                    'description' => $basicData['description'],
                    'app_types' => json_encode($basicData['types']),
                    'genres' => json_encode($basicData['genres']),
                    'app_status' => $basicData['app_status'],
                    'status' => $basicData['status'],
                    'demo_url' => $basicData['demo_url'],
                    'github_url' => $basicData['github_url'],
                    'development_start_date' => $basicData['development_start_date'],
                    'development_end_date' => $basicData['development_end_date'],
                    'development_period_years' => $basicData['development_period_years'],
                    'development_period_months' => $basicData['development_period_months'],
                    'motivation' => $basicData['motivation'],
                    'purpose' => $basicData['purpose']
                ]);

                Log::debug('Basic data saved:', [
                    'app_id' => $app->id,
                    'basic_data' => $basicData
                ]);
            }

            // ストーリー情報の保存（story セクションから取得）
            if ($request->has('formData.story')) {
                $storyData = $request->input('formData.story');

                // 意味のあるデータが1つでもあるかチェック
                $hasValidData = collect($storyData)
                    ->some(function ($value) {
                        return !empty($value);
                    });

                // 意味のあるデータがある場合だけ保存
                if ($hasValidData) {
                    Log::debug('Saving story data:', ['data' => $storyData]);
                    
                    $app->update([
                        'development_trigger' => $storyData['development_trigger'],
                        'development_hardship' => $storyData['development_hardship'],
                        'development_tearful' => $storyData['development_tearful'],
                        'development_enjoyable' => $storyData['development_enjoyable'],
                        'development_funny' => $storyData['development_funny'],
                        'development_impression' => $storyData['development_impression'],
                        'development_oneword' => $storyData['development_oneword']
                    ]);
                } else {
                    Log::debug('Skipping story data save - no valid data');
                }
            }

            // 3枚制限の実装（既存のコードの後に追加）
            $screenshotCount = Screenshot::where('app_id', $app->id)->count();
            if ($screenshotCount > 3) {
                // 3枚を超える古い画像を削除
                Screenshot::where('app_id', $app->id)
                    ->orderByDesc('order')
                    ->skip(3)
                    ->take($screenshotCount - 3)
                    ->delete();
                
                Log::info('超過した画像を削除:', [
                    'app_id' => $app->id,
                    'deleted_count' => $screenshotCount - 3
                ]);
            }

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