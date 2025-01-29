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
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        $app = App::create([
            'user_id' => auth()->id(),
            'title' => config('appv2.constants.app_defaults.title'),
            'status' => config('appv2.constants.app_defaults.status')
        ]);

        return redirect()->route('apps-v2.edit', ['app' => $app]);
    }

    public function store(Request $request)
    {
        Log::debug('Store called', [
            'method' => request()->method(),
            'path' => request()->path(),
            'referer' => request()->headers->get('referer'),
            'app_id' => $request->app_id
        ]);

        // IDが既に存在する場合は新規作成しない
        if ($request->has('app_id')) {
            $app = App::where('id', $request->app_id)
                     ->where('user_id', auth()->id())
                     ->first();
                     
            if ($app) {
                // 既存のアプリを更新
                $app->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'status' => 'draft',
                ]);
                
                return redirect()->route('apps-v2.show', $app)
                               ->with('success', 'アプリ情報を更新しました');
            }
        }

        // 新規作成は初回のみ
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
        // stackチャンネルを使用して両方に出力
        Log::stack(['single', 'daily'])->debug('Edit Controller Called', [
            'method' => request()->method(),
            'path' => request()->path(),
            'referer' => request()->headers->get('referer'),
            'app_id' => $app->id,
            'timestamp' => now()->format('Y-m-d H:i:s.u')
        ]);

        try {
            // 権限チェック
            if ($app->user_id !== auth()->id()) {
                return redirect()->route('apps-v2.index')
                               ->with('error', 'アクセス権限がありません。');
            }

            // セクション情報を取得
            $sections = $this->getSections();
            
            Log::debug('Edit画面表示', [
                'app_id' => $app->id,
                'user_id' => auth()->id()
            ]);

            return view('AppV2::app-form', compact('app', 'sections'));
            
        } catch (\Exception $e) {
            Log::error('Edit画面エラー:', ['error' => $e->getMessage()]);
            return back()->with('error', '画面の表示に失敗しました。');
        }
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

            // ストーリー情報の保存処理を改善
            if ($request->has('formData.story')) {
                $storyData = $request->input('formData.story');
                
                // nullチェックと空文字変換を追加
                $sanitizedStoryData = array_map(function ($value) {
                    return $value === null ? '' : $value;
                }, $storyData);

                Log::debug('Sanitized story data:', ['data' => $sanitizedStoryData]);
                
                $app->update([
                    'development_trigger' => $sanitizedStoryData['development_trigger'],
                    'development_hardship' => $sanitizedStoryData['development_hardship'],
                    'development_tearful' => $sanitizedStoryData['development_tearful'],
                    'development_enjoyable' => $sanitizedStoryData['development_enjoyable'],
                    'development_funny' => $sanitizedStoryData['development_funny'],
                    'development_impression' => $sanitizedStoryData['development_impression'],
                    'development_oneword' => $sanitizedStoryData['development_oneword']
                ]);

                Log::debug('Story data saved successfully');
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
            return response()->json([
                'success' => true,
                'message' => '保存しました'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Autosave error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'エラーが発生しました: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(App $app)
    {
        try {
            DB::beginTransaction();
            
            // アプリに関連するスクリーンショットを削除
            $app->screenshots()->delete();
            
            // アプリを削除
            $app->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'アプリを削除しました'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('App削除エラー:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => '削除中にエラーが発生しました'
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