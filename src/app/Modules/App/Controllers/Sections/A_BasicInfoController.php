<?php

namespace App\Modules\App\Controllers\Sections;

use App\Modules\App\Controllers\Base\SectionController;
use App\Modules\App\Models\App;
use App\Modules\App\Requests\BasicInfoRequest;
use App\Modules\App\Helpers\ColorHelper;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class A_BasicInfoController extends SectionController
{
    public function __construct()
    {
        parent::__construct(app('App\Modules\App\Services\AppProgressManager'));
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

            Log::info('Starting store process', [
                'user_id' => auth()->id(),
                'request_data' => $request->except(['screenshots'])
            ]);

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

            // スクリーンショットの処理を追加
            if ($request->hasFile('screenshots')) {
                $screenshots = [];
                foreach ($request->file('screenshots') as $file) {
                    $url = $this->uploadScreenshot($file);
                    if ($url) {
                        $screenshots[] = $url;
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
            // configから設定を取得
            $config = config('cloudinary.screenshots');
            
            Log::info('Attempting to upload screenshot', [
                'file' => $file->getClientOriginalName(),
                'config' => $config
            ]);

            // アップロードオプションを設定
            $uploadOptions = [
                'folder' => $config['folder'] ?? 'ore_app/screenshots'
            ];

            if (isset($config['transformation'])) {
                $uploadOptions['transformation'] = $config['transformation'];
            }

            // アップロード実行
            $result = Cloudinary::upload($file->getRealPath(), $uploadOptions);

            if (!$result) {
                Log::error('Cloudinary upload returned null result');
                return '';  // nullの代わりに空文字を返す
            }

            $url = $result->getSecurePath();
            
            Log::info('Screenshot upload successful', [
                'file' => $file->getClientOriginalName(),
                'url' => $url
            ]);

            return $url ?: '';  // nullの場合は空文字を返す

        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);
            return '';  // nullの代わりに空文字を返す
        }
    }
} 