<?php

namespace App\Modules\AppPost\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\AppPost\Models\AppPost;
use App\Modules\AppPost\Requests\AppPostRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AppPostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * 一覧表示
     */
    public function index()
    {
        $posts = AppPost::with('user')
            ->where('publish_status', 'public')
            ->latest()
            ->paginate(12);

        return view('AppPost::Cards.index', compact('posts'));
    }

    /**
     * 新規作成フォーム
     */
    public function create()
    {
        return view('AppPost::Forms.create');
    }

    /**
     * 保存処理
     */
    public function store(AppPostRequest $request)
    {
        $validated = $request->validated();

        // スクリーンショットのアップロード処理
        $screenshots = [];
        if ($request->hasFile('screenshots')) {
            foreach ($request->file('screenshots') as $file) {
                $result = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'app_posts',
                ]);
                $screenshots[] = $result->getSecurePath();
            }
        }

        // 投稿の保存
        $post = new AppPost($validated);
        $post->user_id = auth()->id();
        $post->screenshots = $screenshots;
        
        // 各種環境情報の保存
        $post->hardware_info = $request->input('hardware_info', []);
        $post->software_info = $request->input('software_info', []);
        $post->backend_info = $request->input('backend_info', []);
        $post->frontend_info = $request->input('frontend_info', []);
        $post->database_info = $request->input('database_info', []);
        $post->architecture_info = $request->input('architecture_info', []);
        $post->other_info = $request->input('other_info', []);

        $post->save();

        return redirect()
            ->route('app-posts.show', $post)
            ->with('success', 'アプリを投稿しました！');
    }

    /**
     * 詳細表示
     */
    public function show(AppPost $post)
    {
        if ($post->publish_status === 'private' && $post->user_id !== auth()->id()) {
            abort(404);
        }

        return view('AppPost::Details.show', compact('post'));
    }

    /**
     * 編集フォーム
     */
    public function edit(AppPost $post)
    {
        $this->authorize('update', $post);

        return view('AppPost::Forms.edit', compact('post'));
    }

    /**
     * 更新処理
     */
    public function update(AppPostRequest $request, AppPost $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validated();

        // 新しいスクリーンショットのアップロード処理
        $screenshots = $post->screenshots ?? [];
        if ($request->hasFile('screenshots')) {
            foreach ($request->file('screenshots') as $file) {
                $result = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'app_posts',
                ]);
                $screenshots[] = $result->getSecurePath();
            }
        }

        // 投稿の更新
        $post->fill($validated);
        $post->screenshots = $screenshots;
        
        // 各種環境情報の更新
        $post->hardware_info = $request->input('hardware_info', []);
        $post->software_info = $request->input('software_info', []);
        $post->backend_info = $request->input('backend_info', []);
        $post->frontend_info = $request->input('frontend_info', []);
        $post->database_info = $request->input('database_info', []);
        $post->architecture_info = $request->input('architecture_info', []);
        $post->other_info = $request->input('other_info', []);

        $post->save();

        return redirect()
            ->route('app-posts.show', $post)
            ->with('success', 'アプリの情報を更新しました！');
    }

    /**
     * 削除処理
     */
    public function destroy(AppPost $post)
    {
        $this->authorize('delete', $post);

        // Cloudinaryの画像を削除
        if ($post->screenshots) {
            foreach ($post->screenshots as $url) {
                $publicId = last(explode('/', parse_url($url, PHP_URL_PATH)));
                Cloudinary::destroy($publicId);
            }
        }

        $post->delete();

        return redirect()
            ->route('app-posts.index')
            ->with('success', 'アプリを削除しました。');
    }
} 