<?php

namespace App\Modules\App\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AppController extends Controller
{
    public function create()
    {
        $app = new App();
        return view('App::create', ['app' => $app]);
    }

    public function store(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'screenshots.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
            // ... 他のバリデーションルール ...
        ]);

        try {
            // スクリーンショットの処理
            $screenshots = [];
            if ($request->hasFile('screenshots')) {
                foreach ($request->file('screenshots') as $file) {
                    // Cloudinaryにアップロード
                    $result = Cloudinary::upload($file->getRealPath(), [
                        'folder' => 'app_screenshots',
                        'transformation' => [
                            'quality' => 'auto',
                            'fetch_format' => 'auto',
                        ]
                    ]);
                    $screenshots[] = $result->getSecurePath();
                }
            }

            // アプリの保存処理
            $app = new App($validated);
            $app->screenshots = $screenshots;
            $app->save();

            return redirect()->route('apps.show', $app)
                ->with('success', 'アプリを登録しました');

        } catch (\Exception $e) {
            // エラー時は既にアップロードした画像を削除
            foreach ($screenshots as $url) {
                $publicId = Cloudinary::getPublicId($url);
                if ($publicId) {
                    Cloudinary::destroy($publicId);
                }
            }

            return back()
                ->withInput()
                ->with('error', '保存に失敗しました: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $app = App::findOrFail($id);
        return view('App::edit', ['app' => $app]);
    }

    public function update(Request $request, $id)
    {
        $app = App::findOrFail($id);

        // バリデーション
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'screenshots.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            // ... 他のバリデーションルール ...
        ]);

        try {
            // 既存のスクリーンショットを保持
            $screenshots = $app->screenshots ?? [];

            // 削除された画像の処理
            $existingScreenshots = $request->input('existing_screenshots', []);
            foreach ($screenshots as $screenshot) {
                if (!in_array($screenshot, $existingScreenshots)) {
                    $publicId = Cloudinary::getPublicId($screenshot);
                    if ($publicId) {
                        Cloudinary::destroy($publicId);
                    }
                }
            }

            // 新しい画像のアップロード
            if ($request->hasFile('screenshots')) {
                foreach ($request->file('screenshots') as $file) {
                    $result = Cloudinary::upload($file->getRealPath(), [
                        'folder' => 'app_screenshots',
                        'transformation' => [
                            'quality' => 'auto',
                            'fetch_format' => 'auto',
                        ]
                    ]);
                    $screenshots[] = $result->getSecurePath();
                }
            }

            // アプリの更新
            $app->fill($validated);
            $app->screenshots = array_values($existingScreenshots);
            $app->save();

            return redirect()->route('apps.show', $app)
                ->with('success', 'アプリを更新しました');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', '更新に失敗しました: ' . $e->getMessage());
        }
    }

    public function index()
    {
        return view('App::index');
    }

    public function show($id)
    {
        $app = new \stdClass(); // 後で実データに置き換え
        return view('App::show', compact('app'));
    }
} 