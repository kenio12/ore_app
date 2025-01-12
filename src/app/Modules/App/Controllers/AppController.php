<?php

namespace App\Modules\App\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AppController extends Controller
{
    private array $sections = [
        'basic-info',
        'development-story',
        'hardware',
        'dev-tools',
        'architecture',
        'security',
        'backend',
        'frontend',
        'database'
    ];

    public function create(Request $request, string $section = 'basic-info')
    {
        // セクションの存在確認
        if (!in_array($section, $this->sections)) {
            return redirect()->route('app.create');
        }

        // 現在のインデックスを取得
        $currentIndex = array_search($section, $this->sections);
        
        // 前後のセクションを決定
        $previousSection = $currentIndex > 0 ? $this->sections[$currentIndex - 1] : null;
        $nextSection = isset($this->sections[$currentIndex + 1]) ? $this->sections[$currentIndex + 1] : null;

        // セクションのタイトルマップ
        $sectionTitles = [
            'basic-info' => '基本情報',
            'development-story' => '開発ストーリー',
            'hardware' => 'ハードウェア環境',
            'dev-tools' => '開発ツール環境',
            'architecture' => 'アーキテクチャ',
            'security' => 'セキュリティと品質管理',
            'backend' => 'バックエンド環境',
            'frontend' => 'フロントエンド環境',
            'database' => 'データベース環境'
        ];

        return view('app::app-form', [
            'currentSection' => $section,
            'previousSection' => $previousSection,
            'nextSection' => $nextSection,
            'sectionTitle' => $sectionTitles[$section] ?? '不明なセクション',
            'sections' => $sectionTitles,
            'app' => new App()
        ]);
    }

    private function uploadScreenshot($file)
    {
        // 画質80%固定で安定した表示を実現
        $result = Cloudinary::upload($file->getRealPath(), [
            'folder' => 'app_screenshots',
            'transformation' => [
                'quality' => 80,  // 80%固定
                'fetch_format' => 'auto'
            ]
        ]);

        return $result->getSecurePath();
    }

    public function store(Request $request, string $section)
    {
        // セッションにフォームデータを保存
        $request->session()->put("app_form.{$section}", $request->all());
        
        // 次のセクションを決定
        $nextSection = $this->getNextSection($section);
        
        // 最後のセクションなら、全データを保存
        if (!$nextSection) {
            return $this->saveAllSections($request);
        }
        
        // 次のセクションへリダイレクト
        return redirect()->route('app.create', ['section' => $nextSection])
                        ->with('success', 'セクションを保存しました！');
    }

    private function getNextSection(string $currentSection): ?string
    {
        $sections = [
            'basic-info',
            'development-story',
            'hardware',
            'dev-tools',
            'architecture',
            'security',
            'backend',
            'frontend',
            'database'
        ];
        
        $currentIndex = array_search($currentSection, $sections);
        return isset($sections[$currentIndex + 1]) ? $sections[$currentIndex + 1] : null;
    }

    private function saveAllSections(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // appsテーブルにメインレコード作成
            $app = App::create([
                'user_id' => auth()->id(),
                'uuid' => Str::uuid()
            ]);

            // 各セクションのデータを保存
            $this->saveBasicInfo($app, $request);
            $this->saveDevelopmentStory($app, $request);
            // ... 他のセクションも同様に保存

            DB::commit();
            
            // セッションデータをクリア
            $request->session()->forget('app_form');
            
            return redirect()->route('app.show', $app->uuid)
                           ->with('success', 'アプリを登録しました！');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '登録に失敗しました。');
        }
    }

    public function editBasicInfo(App $app)
    {
        return view('app::app-form', [
            'app' => $app,
            'currentSection' => 'basic-info',
            'sectionTitle' => '基本情報'
        ]);
    }

    public function updateBasicInfo(BasicInfoRequest $request, App $app)
    {
        $app->basicInfo()->updateOrCreate(
            ['app_id' => $app->id],
            $request->validated()
        );

        return redirect()
            ->route('apps.development-story.edit', $app)
            ->with('success', '基本情報を保存しました');
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