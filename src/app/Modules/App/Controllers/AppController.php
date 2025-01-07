<?php

namespace App\Modules\App\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    public function create()
    {
        $app = new App();
        return view('App::create', ['app' => $app]);
    }

    private function uploadScreenshot($file)
    {
        // オリジナル画像をアップロード
        $result = Cloudinary::upload($file->getRealPath(), [
            'folder' => 'app_screenshots',
            'transformation' => [
                'quality' => 'auto',
                'fetch_format' => 'auto',
            ]
        ]);

        // サムネイル用の変換を追加
        $thumbnail = Cloudinary::upload($file->getRealPath(), [
            'folder' => 'app_screenshots/thumbnails',
            'transformation' => [
                'width' => 300,
                'height' => 200,
                'crop' => 'fill',
                'quality' => 'auto',
                'fetch_format' => 'auto',
            ]
        ]);

        return [
            'original' => $result->getSecurePath(),
            'thumbnail' => $thumbnail->getSecurePath()
        ];
    }

    public function store(Request $request)
    {
        try {
            // バリデーション
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'demo_url' => 'nullable|url|max:255',
                'github_url' => 'nullable|url|max:255',
                'status' => 'required|in:published,draft',  // publish_status から status に変更
                'app_status' => 'required|in:completed,in_development',
                'screenshots' => 'required|array|min:1|max:3',
                'screenshots.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);

            // スクリーンショットの処理
            $screenshots = [];
            if ($request->hasFile('screenshots')) {
                foreach ($request->file('screenshots') as $file) {
                    $result = $this->uploadScreenshot($file);
                    $screenshots[] = $result['original'];
                }
            }

            // アプリの保存
            $app = new App();
            $app->fill($validated);
            $app->screenshots = $screenshots;
            $app->user_id = Auth::id();
            $app->save();

            // statusによって遷移先を変更
            if ($app->status === 'published') {
                // 公開する場合はトップページへ
                return redirect('/')
                    ->with('success', 'アプリを公開しました！');
            } else {
                // 下書きの場合は自身のプロフィールへ
                return redirect()->route('profile.index')
                    ->with('success', 'アプリを保存しました！');
            }

        } catch (\Exception $e) {
            return back()
                ->withInput($request->except('screenshots'))
                ->with('error', 'エラーが発生しました：' . $e->getMessage());
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

        // 投稿者本人のみ更新可能
        if ($app->user_id !== Auth::id()) {
            abort(403, '更新権限がありません。');
        }

        // バリデーション
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'demo_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'publish_status' => 'required|in:published,draft',  // 公開状態
            'app_status' => 'required|in:completed,in_development',  // アプリの状態
            'existing_screenshots' => 'array',
            'screenshots' => 'array|max:3',
            'screenshots.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            // 既存のスクリーンショットを保持
            $screenshots = [];
            
            // 既存の画像を処理
            $existingScreenshots = $request->input('existing_screenshots', []);
            foreach ($app->screenshots as $screenshot) {
                if (in_array($screenshot, $existingScreenshots)) {
                    $screenshots[] = $screenshot;
                } else {
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

            // スクリーンショットが1枚もない場合はエラー
            if (empty($screenshots)) {
                throw new \Exception('スクリーンショットは最低1枚必要です。');
            }

            // アプリの更新
            $app->fill($validated);
            $app->screenshots = $screenshots;
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
        $app = App::with('user')->findOrFail($id);  // ユーザー情報も取得
        return view('App::show', compact('app'));
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'demo_url' => 'required|url',
            'github_url' => 'required|url',
            'status' => 'required|in:draft,published',  // publish_status から status に変更
            'screenshots' => 'required|array|min:1|max:3',
            'screenshots.*' => 'image|max:5120', // 5MB
        ];
    }
} 