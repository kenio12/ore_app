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

class A_BasicInfoController extends SectionController
{
    private CloudinaryService $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        parent::__construct(app('App\Modules\App\Services\AppProgressManager'));
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
                // 既存のスクリーンショットを取得
                $screenshots = $app->screenshots ?? [];
                
                // 新しいスクリーンショットを追加
                foreach ($request->file('screenshots') as $file) {
                    $newScreenshot = $this->uploadScreenshot($file);
                    if ($newScreenshot) {
                        $screenshots[] = $newScreenshot;
                    }
                }
                
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
        // バリデーションを実行して結果を取得
        $validatedData = app(BasicInfoRequest::class)->validated();
        
        try {
            DB::beginTransaction();

            // バリデーション済みデータを使用して新規作成
            $app = new App(array_merge($validatedData, [
                'color' => ColorHelper::generateColorFromString($validatedData['title']),
                'user_id' => auth()->id()
            ]));

            $app->save();

            // スクリーンショットの処理を改善
            if ($request->hasFile('screenshots')) {
                $screenshots = [];
                foreach ($request->file('screenshots') as $file) {
                    try {
                        $tempResult = $this->uploadScreenshot($file);
                        if ($tempResult) {
                            $result = $this->cloudinaryService->moveToProduction($tempResult['temp_public_id']);
                            $screenshots[] = [
                                'public_id' => $result['public_id'],
                                'url' => $result['url'],
                                'width' => $result['width'],
                                'height' => $result['height']
                            ];
                            \Log::info('Screenshot processed successfully', ['result' => $result]);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error processing screenshot', [
                            'error' => $e->getMessage(),
                            'file' => $file->getClientOriginalName()
                        ]);
                        continue;  // エラーが発生しても次の画像の処理を続行
                    }
                }
                
                if (!empty($screenshots)) {
                    $app->screenshots = $screenshots;
                    $app->save();
                    \Log::info('All screenshots saved', ['count' => count($screenshots)]);
                }
            }

            $this->completeSection($app->id, 'basic-info');

            DB::commit();

            return redirect()
                ->route('app.sections.development-story.edit', ['app' => $app->id])
                ->with('success', '基本情報を保存しました！');

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function uploadScreenshot($file)
    {
        if (!$file || !$file->isValid()) {
            Log::warning('Invalid file provided for upload', [
                'file' => $file ? $file->getClientOriginalName() : 'null'
            ]);
            return null;
        }

        try {
            // CloudinaryServiceを使用して一時保存
            $result = $this->cloudinaryService->uploadToTemp($file);
            
            Log::info('Screenshot upload successful', [
                'file' => $file->getClientOriginalName(),
                'result' => $result
            ]);

            return [
                'temp_public_id' => $result['temp_public_id'],
                'url' => $result['url'],
                'width' => $result['width'],
                'height' => $result['height']
            ];

        } catch (\Exception $e) {
            Log::error('Screenshot upload failed', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;  // 上位で処理するために例外を投げる
        }
    }
} 