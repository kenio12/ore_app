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

    public function update(FormRequest $request, string $appId)
    {
        try {
            DB::beginTransaction();

            $app = App::findOrFail($appId);
            
            // 基本情報の更新
            $app->update([
                'title' => $request->title,
                'description' => $request->description,
                'demo_url' => $request->demo_url,
                'github_url' => $request->github_url,
                'status' => $request->status,
                'color' => ColorHelper::generateColorFromString($request->title)
            ]);

            // スクリーンショットの処理
            if ($request->hasFile('screenshots')) {
                $screenshots = [];
                foreach ($request->file('screenshots') as $file) {
                    $screenshots[] = $this->uploadScreenshot($file);
                }
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

    public function store(FormRequest $request)
    {
        try {
            DB::beginTransaction();

            // 基本情報の保存
            $app = new App([
                'title' => $request->title,
                'description' => $request->description,
                'demo_url' => $request->demo_url,
                'github_url' => $request->github_url,
                'status' => $request->status,
                'color' => ColorHelper::generateColorFromString($request->title),
                'user_id' => auth()->id()
            ]);

            $app->save();

            // スクリーンショットの処理
            if ($request->hasFile('screenshots')) {
                $screenshots = [];
                foreach ($request->file('screenshots') as $file) {
                    $tempResult = $this->uploadScreenshot($file);
                    if ($tempResult) {
                        // 一時保存から本番環境への移動
                        $result = $this->cloudinaryService->moveToProduction($tempResult['temp_public_id']);
                        $screenshots[] = [
                            'public_id' => $result['public_id'],
                            'url' => $result['url'],
                            'width' => $result['width'],
                            'height' => $result['height']
                        ];
                    }
                }
                if (!empty($screenshots)) {
                    $app->screenshots = $screenshots;
                    $app->save();
                }
            }

            $this->completeSection($app->id, 'basic-info');

            DB::commit();

            return redirect()
                ->route('app.sections.development-story.edit', ['app' => $app->id])
                ->with('success', '基本情報を保存しました！');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store process failed', [
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