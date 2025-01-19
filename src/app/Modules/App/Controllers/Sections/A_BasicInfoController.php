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
                Log::info('Processing screenshots', [
                    'files_count' => count($request->file('screenshots')),
                    'request_files' => $request->file('screenshots')
                ]);

                // 既存のスクリーンショットを取得
                $screenshots = $app->screenshots ?? [];
                Log::info('Existing screenshots before update', [
                    'screenshots' => $screenshots,
                    'app_id' => $app->id
                ]);
                
                // 新しいスクリーンショットを追加
                foreach ($request->file('screenshots') as $file) {
                    Log::info('Processing individual screenshot', [
                        'filename' => $file->getClientOriginalName(),
                        'size' => $file->getSize()
                    ]);

                    $newScreenshot = $this->uploadScreenshot($file);
                    if ($newScreenshot) {
                        $screenshots[] = $newScreenshot;
                        Log::info('Added new screenshot to array', [
                            'new_screenshot' => $newScreenshot,
                            'current_screenshots_count' => count($screenshots)
                        ]);
                    }
                }
                
                // 最大3枚までに制限
                $screenshots = array_slice($screenshots, 0, 3);
                
                // 保存前の状態をログ
                Log::info('About to save screenshots', [
                    'app_id' => $app->id,
                    'screenshots_to_save' => $screenshots,
                    'screenshots_count' => count($screenshots)
                ]);

                $app->screenshots = $screenshots;
                
                // 保存直前の状態をログ
                Log::info('App model state before save', [
                    'app_id' => $app->id,
                    'screenshots' => $app->screenshots,
                    'isDirty' => $app->isDirty(),
                    'dirtyAttributes' => $app->getDirty()
                ]);
                
                $app->save();

                // 保存後の状態を確認
                $app->refresh();
                Log::info('App model state after save', [
                    'app_id' => $app->id,
                    'screenshots' => $app->screenshots
                ]);
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
            // より詳細なリクエストの内容をログに記録
            Log::info('Store method called with request:', [
                'has_files' => $request->hasFile('screenshots'),
                'all_data' => $request->all(),
                'files' => $request->allFiles(),
                'development_start_date' => $request->input('development_start_date'),
                'cloudinary_config' => [
                    'cloud_name' => config('cloudinary.cloud_name'),
                    'api_key' => config('cloudinary.api_key'),
                    'has_secret' => !empty(config('cloudinary.api_secret')),
                    'secure' => config('cloudinary.secure'),
                    'folder' => config('cloudinary.folder')
                ]
            ]);

            DB::beginTransaction();

            // 1. アプリの基本情報を保存
            $app = new App();
            $validatedData = app(BasicInfoRequest::class)->validated();
            
            $app->fill(array_merge($validatedData, [
                'color' => ColorHelper::generateColorFromString($validatedData['title']),
                'user_id' => auth()->id()
            ]));
            $app->save();

            // スクリーンショットの処理
            if ($request->hasFile('screenshots')) {
                Log::info('Processing screenshots', [
                    'files_count' => count($request->file('screenshots')),
                    'request_files' => $request->file('screenshots')
                ]);

                // 既存のスクリーンショットを取得
                $screenshots = $app->screenshots ?? [];
                Log::info('Existing screenshots before update', [
                    'screenshots' => $screenshots,
                    'app_id' => $app->id
                ]);
                
                // 新しいスクリーンショットを追加
                foreach ($request->file('screenshots') as $file) {
                    Log::info('Processing individual screenshot', [
                        'filename' => $file->getClientOriginalName(),
                        'size' => $file->getSize()
                    ]);

                    $newScreenshot = $this->uploadScreenshot($file);
                    if ($newScreenshot) {
                        $screenshots[] = $newScreenshot;
                        Log::info('Added new screenshot to array', [
                            'new_screenshot' => $newScreenshot,
                            'current_screenshots_count' => count($screenshots)
                        ]);
                    }
                }
                
                // 最大3枚までに制限
                $screenshots = array_slice($screenshots, 0, 3);
                
                // 保存前の状態をログ
                Log::info('About to save screenshots', [
                    'app_id' => $app->id,
                    'screenshots_to_save' => $screenshots,
                    'screenshots_count' => count($screenshots)
                ]);

                $app->screenshots = $screenshots;
                
                // 保存直前の状態をログ
                Log::info('App model state before save', [
                    'app_id' => $app->id,
                    'screenshots' => $app->screenshots,
                    'isDirty' => $app->isDirty(),
                    'dirtyAttributes' => $app->getDirty()
                ]);
                
                $app->save();

                // 保存後の状態を確認
                $app->refresh();
                Log::info('App model state after save', [
                    'app_id' => $app->id,
                    'screenshots' => $app->screenshots
                ]);
            }

            // 2. セクションを完了マーク
            $this->completeSection($app->id, 'basic-info');

            DB::commit();

            // 3. 次のセクションへリダイレクト（SectionControllerのnextメソッドを使用）
            return $this->next($app->id);

        } catch (\Exception $e) {
            DB::rollBack();
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