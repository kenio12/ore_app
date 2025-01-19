<?php

namespace App\Modules\App\Controllers\Sections;

use App\Modules\App\Controllers\Base\SectionController;
use App\Modules\App\Models\App;
use App\Modules\App\Requests\BasicInfoRequest;
use App\Modules\App\Helpers\ColorHelper;
use App\Modules\App\Services\CloudinaryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
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
        $app = App::findOrFail($appId);
        return view('app::Forms.01_BasicInfoForm', [
            'app' => $app,
            'currentSection' => 'basic-info'
        ]);
    }

    public function update(Request $request, string $appId)
    {
        // バリデーションを実行して結果を取得
        $validatedData = app(BasicInfoRequest::class)->validated();
        
        try {
            DB::beginTransaction();

            $app = App::findOrFail($appId);
            
            // バリデーション済みデータを使用して更新
            $app->update(array_merge($validatedData, [
                'color' => ColorHelper::generateColorFromString($validatedData['title']),
                'user_id' => auth()->id()
            ]));

            // スクリーンショットの処理
            if ($request->hasFile('screenshots')) {
                $files = $request->file('screenshots');
                
                // 最大3枚までに制限
                if (count($files) > 3) {
                    $files = array_slice($files, 0, 3);
                }

                // 一括アップロード
                $uploadResults = $this->cloudinaryService->uploadMultipleToTemp($files);
                
                // 既存のスクリーンショットを取得
                $screenshots = [];
                if (!empty($app->screenshots) && is_array($app->screenshots)) {
                    foreach ($app->screenshots as $screenshot) {
                        if (!empty($screenshot['url'])) {
                            $screenshots[] = $screenshot;
                        }
                    }
                }

                // 新しいスクリーンショットを追加
                $screenshots = array_merge($screenshots, $uploadResults);
                
                // 最大3枚までに制限
                $screenshots = array_slice($screenshots, 0, 3);
                
                $app->screenshots = $screenshots;
                $app->save();
            }

            // セクション完了をマーク
            $this->completeSection($appId, 'basic-info');

            DB::commit();

            return redirect()
                ->route('app.sections.development-story.edit', $appId)
                ->with('success', '基本情報を保存しました！');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'データの保存に失敗しました: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = app(BasicInfoRequest::class)->validated();
            
            $app = new App();
            $app->fill(array_merge($validatedData, [
                'color' => ColorHelper::generateColorFromString($validatedData['title']),
                'user_id' => auth()->id()
            ]));
            
            $screenshots = [];
            
            if ($request->hasFile('screenshots')) {
                $files = $request->file('screenshots');
                
                Log::info('Received files:', [
                    'count' => count($files),
                    'files' => array_map(function($file) {
                        return $file->getClientOriginalName();
                    }, $files)
                ]);
                
                // 最大3枚までに制限
                if (count($files) > 3) {
                    $files = array_slice($files, 0, 3);
                }

                // 一括アップロード
                $uploadResults = $this->cloudinaryService->uploadMultipleToTemp($files);
                
                Log::info('Upload results:', [
                    'count' => count($uploadResults),
                    'results' => $uploadResults
                ]);

                $screenshots = $uploadResults;
                
                Log::info('Saved screenshots:', [
                    'app_id' => $app->id,
                    'screenshots' => $screenshots
                ]);
            }
            
            $app->screenshots = $screenshots;
            $app->save();
            
            DB::commit();

            return $this->next($app->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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