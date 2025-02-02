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
use Illuminate\Support\Facades\Auth;

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
        $initialFormData = [
            'basic' => [
                'title' => '',
                'description' => '',
                'app_types' => [],
                'genres' => [],
                'app_status' => 'draft',
                'status' => 'draft',
                'demo_url' => '',
                'github_url' => '',
                'development_start_date' => '',
                'development_end_date' => '',
                'development_period_years' => 0,
                'development_period_months' => 0,
                'motivation' => '',
                'purpose' => ''
            ],
            'screenshots' => [],
            'story' => [
                'development_trigger' => '',
                'development_hardship' => '',
                'development_tearful' => '',
                'development_enjoyable' => '',
                'development_funny' => '',
                'development_impression' => '',
                'development_oneword' => ''
            ],
            'hardware' => [
                'cpu' => '',
                'memory' => '',
                'storage' => ''
            ],
            'dev_env' => [
                'editor' => '',
                'version_control' => '',
                'ci_cd' => ''
            ],
            'architecture' => [
                'description' => '',
                'patterns' => [],
                'design_patterns' => []
            ],
            'frontend' => [
                'framework' => '',
                'ui_library' => ''
            ],
            'backend' => [
                'languages' => [],
                'frameworks' => [],
                'packages' => ''
            ],
            'database' => [
                'type' => '',
                'design' => ''
            ],
            'security' => [
                'auth' => '',
                'measures' => ''
            ]
        ];

        return view('AppV2::app-form', ['formData' => $initialFormData]);
    }

    public function store(Request $request)
    {
        $app = App::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'status' => 'draft'
        ]);

        return response()->json([
            'id' => $app->id,
            'message' => '保存しました'
        ]);
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

    public function edit($id)
    {
        $sections = [
            'basic' => [
                'title' => 'アプリの基本情報',
                'icon' => 'information-circle'
            ],
            'screenshots' => [
                'title' => 'スクリーンショット',
                'icon' => 'photograph'
            ]
            // 他のセクションも必要に応じて追加
        ];

        $app = App::findOrFail($id);
        $formData = [
            'basic' => [
                'title' => $app->title,
                // 他の基本情報フィールド
            ],
            // 他のセクション
        ];
        $appId = $app->id;

        return view('AppV2::app-form', compact('formData', 'appId', 'app', 'sections'));
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
                
                // タイトルが空の場合は何もせずに正常終了
                if (empty($basicData['title'])) {
                    DB::rollBack();
                    return response()->json([
                        'success' => true,  // エラーではないので true
                        'message' => null   // メッセージも表示しない
                    ]);
                }
                
                // タイトルがある場合のみ保存処理を実行
                $app->update([
                    'title' => $basicData['title'],
                    'description' => $basicData['description'],
                    'app_types' => json_encode($basicData['types']),
                    'genres' => json_encode($basicData['genres']),
                    'app_status' => $basicData['app_status'],
                    'status' => $basicData['status'],
                    'app_url' => $basicData['app_url'],
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
            'basic' => [
                'title' => 'アプリの基本情報',
                'icon' => 'information-circle'
            ],
            'screenshots' => [
                'title' => 'スクリーンショット',
                'icon' => 'photograph'
            ],
            'story' => [
                'title' => '開発ストーリー',
                'icon' => 'book-open'
            ],
            'hardware' => [
                'title' => 'ハードウェア要件',
                'icon' => 'chip'
            ],
            'dev_env' => [
                'title' => '開発環境',
                'icon' => 'code'
            ],
            'architecture' => [
                'title' => 'アーキテクチャ',
                'icon' => 'template'
            ],
            'frontend' => [
                'title' => 'フロントエンド',
                'icon' => 'desktop-computer'
            ],
            'backend' => [
                'title' => 'バックエンド',
                'icon' => 'server'
            ],
            'database' => [
                'title' => 'データベース',
                'icon' => 'database'
            ],
            'security' => [
                'title' => 'セキュリティ',
                'icon' => 'shield-check'
            ]
        ];
    }

    private function saveRelatedData(App $app, Request $request)
    {
        // 各セクションのデータを保存
        // 実装は後ほど詳細を詰めます
    }

    // タイトル入力時の処理を追加
    public function createWithTitle(Request $request)
    {
        try {
            $title = $request->input('title');
            
            // 初期データ構造を定義
            $initialData = [
                'basic' => [
                    'title' => $title,
                    'description' => '',
                    'app_types' => [],
                    'genres' => [],
                    'app_status' => 'draft',
                    'status' => 'draft',
                    'demo_url' => '',
                    'github_url' => '',
                    'development_start_date' => '',
                    'development_end_date' => '',
                    'development_period_years' => 0,
                    'development_period_months' => 0
                ],
                'screenshots' => [],
                'story' => [
                    'development_trigger' => '',
                    'development_hardship' => '',
                    'development_tearful' => '',
                    'development_enjoyable' => '',
                    'development_funny' => '',
                    'development_impression' => '',
                    'development_oneword' => ''
                ],
                'hardware' => [
                    'cpu' => '',
                    'memory' => '',
                    'storage' => ''
                ],
                'dev_env' => [
                    'editor' => '',
                    'version_control' => '',
                    'ci_cd' => ''
                ],
                'architecture' => [
                    'description' => '',
                    'patterns' => [],
                    'design_patterns' => []
                ],
                'frontend' => [
                    'framework' => '',
                    'ui_library' => ''
                ],
                'backend' => [
                    'languages' => [],
                    'frameworks' => [],
                    'packages' => ''
                ],
                'database' => [
                    'type' => '',
                    'design' => ''
                ],
                'security' => [
                    'auth' => '',
                    'measures' => ''
                ]
            ];
            
            // アプリを作成
            $app = App::create([
                'user_id' => Auth::id(),
                'title' => $title,
                'status' => 'draft',
                'completed_sections' => [],
                'data' => $initialData
            ]);
            
            Log::info('App created:', ['id' => $app->id, 'title' => $title]);
            
            return response()->json([
                'id' => $app->id,
                'message' => 'App created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('App creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, App $app)
    {
        $app->update($request->all());
        
        return response()->json([
            'message' => '更新しました'
        ]);
    }
} 