<?php

namespace App\Modules\App\Controllers\Sections;

use App\Modules\App\Controllers\Base\SectionController;
use App\Modules\App\Models\App;
use App\Modules\App\Helpers\ColorHelper;
use App\Modules\App\Services\CloudinaryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Modules\App\Services\AppProgressManager;

class A_BasicInfoController extends SectionController
{
    private CloudinaryService $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService, AppProgressManager $progressManager)
    {
        parent::__construct($progressManager);
        $this->cloudinaryService = $cloudinaryService;
    }

    public function edit(string $appId)
    {
        try {
            $app = App::findOrFail($appId);
            
            // 権限チェック
            if ($app->user_id !== auth()->id()) {
                throw new \Exception('編集権限がありません。');
            }

            // スクリーンショットデータの準備
            $screenshots = [];
            if (!empty($app->screenshots) && is_array($app->screenshots)) {
                foreach ($app->screenshots as $screenshot) {
                    if (!empty($screenshot['url'])) {
                        $screenshots[] = [
                            'url' => $screenshot['url'],
                            'public_id' => $screenshot['public_id'] ?? null,
                            'width' => $screenshot['width'] ?? null,
                            'height' => $screenshot['height'] ?? null
                        ];
                    }
                }
            }

            Log::info('Editing app with screenshots:', [
                'app_id' => $appId,
                'screenshots' => $screenshots
            ]);

            return view('app::Forms.01_BasicInfoForm', [
                'app' => $app,
                'currentSection' => 'basic-info',
                'screenshots' => $screenshots,
                'viewOnly' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Edit failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', '編集画面の表示に失敗しました: ' . $e->getMessage());
        }
    }

    public function update(Request $request, string $appId)
    {
        // FormRequestを直接インスタンス化して検証
        $formRequest = new \App\Modules\App\Requests\_01BasicInfoRequest();
        $formRequest->setContainer(app());  // コンテナをセット
        $formRequest->initialize($request->all());  // リクエストデータを初期化
        $formRequest->validateResolved();  // バリデーション実行
        $validatedData = $request->all();  // 全データを取得

        try {
            DB::beginTransaction();

            $app = App::findOrFail($appId);
            
            // 権限チェック
            if ($app->user_id !== auth()->id()) {
                throw new \Exception('更新権限がありません。');
            }

            // 基本データの更新
            $app->update(array_merge($validatedData, [
                'color' => ColorHelper::generateColorFromString($validatedData['title'])
            ]));

            // スクリーンショットの処理
            $screenshots = [];

            // 既存のスクリーンショットを保持（削除されていないもののみ）
            if (!empty($app->screenshots) && is_array($app->screenshots)) {
                $keepScreenshots = $request->input('keep_screenshots', []);
                foreach ($app->screenshots as $index => $screenshot) {
                    if (in_array($index, $keepScreenshots) && !empty($screenshot['url'])) {
                        $screenshots[] = $screenshot;
                    } else {
                        // Cloudinaryから削除
                        if (!empty($screenshot['public_id'])) {
                            $this->cloudinaryService->delete($screenshot['public_id']);
                        }
                    }
                }
            }

            // 新しいスクリーンショットの追加
            if ($request->hasFile('screenshots')) {
                $files = $request->file('screenshots');
                
                // 既存と新規の合計が3枚を超えないようにチェック
                $remainingSlots = 3 - count($screenshots);
                if (count($files) > $remainingSlots) {
                    $files = array_slice($files, 0, $remainingSlots);
                }

                // アップロード
                $uploadResults = $this->cloudinaryService->uploadMultipleToTemp($files);
                $screenshots = array_merge($screenshots, $uploadResults);
            }

            // スクリーンショットを保存
            $app->screenshots = $screenshots;
            $app->save();

            // セクション完了をマーク
            $this->completeSection($appId, 'basic-info');

            DB::commit();

            return redirect()
                ->route('app.sections.development-story.edit', $appId)
                ->with('success', '基本情報を更新しました！');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->withInput()
                ->with('error', 'データの更新に失敗しました: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // ファイルの詳細な情報をログ
            if ($request->hasFile('screenshots')) {
                $files = $request->file('screenshots');
                Log::info('詳細なファイル情報:', [
                    'ファイル数' => count($files),
                    'ファイル詳細' => array_map(function($file) {
                        return [
                            'name' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                            'mime' => $file->getMimeType(),
                            'path' => $file->getRealPath(),
                            'error' => $file->getError(),
                            'extension' => $file->getClientOriginalExtension()
                        ];
                    }, $files)
                ]);
            } else {
                Log::warning('ファイルが見つかりません');
                Log::info('リクエスト内容:', [
                    'has_files' => $request->hasFile('screenshots'),
                    'all_input' => $request->all(),
                    'files' => $request->files->all()
                ]);
            }

            $validatedData = app(_01BasicInfoRequest::class)->validated();
            
            // バリデーション後のデータをログ
            Log::info('バリデーション後のデータ:', [
                'validated' => $validatedData
            ]);

            $app = new App();
            $app->fill(array_merge($validatedData, [
                'color' => ColorHelper::generateColorFromString($validatedData['title']),
                'user_id' => auth()->id()
            ]));
            
            $screenshots = [];
            
            if ($request->hasFile('screenshots')) {
                $files = $request->file('screenshots');
                
                // アップロード前の状態確認
                Log::info('アップロード前のファイル状態:', [
                    'count' => count($files),
                    'files' => array_map(function($file) {
                        return [
                            'name' => $file->getClientOriginalName(),
                            'valid' => $file->isValid(),
                            'error' => $file->getError()
                        ];
                    }, $files)
                ]);
                
                if (count($files) > 3) {
                    $files = array_slice($files, 0, 3);
                }

                // CloudinaryServiceに渡す直前の状態
                Log::info('Cloudinaryアップロード直前:', [
                    'files_to_upload' => array_map(function($file) {
                        return [
                            'name' => $file->getClientOriginalName(),
                            'path' => $file->getRealPath(),
                            'temp_path' => $file->getPathname()
                        ];
                    }, $files)
                ]);

                $uploadResults = $this->cloudinaryService->uploadMultipleToTemp($files);
                
                // アップロード結果の詳細ログ
                Log::info('Cloudinaryアップロード結果:', [
                    'success_count' => count($uploadResults),
                    'results' => $uploadResults
                ]);

                $screenshots = $uploadResults;
            }
            
            // 最終保存前の状態確認
            Log::info('保存前の最終状態:', [
                'app_id' => $app->id,
                'screenshots' => $screenshots
            ]);
            
            $app->screenshots = $screenshots;
            $app->save();
            
            DB::commit();

            return $this->next($app->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('保存失敗:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return back()
                ->withInput()
                ->with('error', 'データの保存に失敗しました: ' . $e->getMessage());
        }
    }

    private function uploadScreenshot($file)
    {
        if (!$file || !$file->isValid()) {
            Log::warning('Invalid file provided for upload', [
                'file' => $file ? $file->getClientOriginalName() : 'null',
                'error' => $file ? $file->getError() : 'null'
            ]);
            return null;
        }

        try {
            Log::info('Attempting to upload file', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType()
            ]);

            $result = $this->cloudinaryService->uploadToTemp($file);
            
            // 詳細なログ出力を追加
            Log::info('Upload result details:', [
                'raw_result' => $result,
                'public_id' => $result['public_id'] ?? null,
                'url' => $result['url'] ?? null,
                'width' => $result['width'] ?? null,
                'height' => $result['height'] ?? null
            ]);

            $screenshot = [
                'public_id' => $result['public_id'],
                'url' => $result['url'],
                'width' => $result['width'],
                'height' => $result['height']
            ];

            Log::info('Returning screenshot data:', [
                'screenshot' => $screenshot
            ]);

            return $screenshot;

        } catch (\Exception $e) {
            Log::error('Screenshot upload failed:', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function show(string $appId)
    {
        $app = App::findOrFail($appId);
        return view('app::Forms.01_BasicInfoForm', [
            'app' => $app,
            'currentSection' => 'basic-info',
            'viewOnly' => true
        ]);
    }
} 