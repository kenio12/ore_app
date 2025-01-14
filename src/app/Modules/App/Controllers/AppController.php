<?php

namespace App\Modules\App\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\App\Models\App;
use App\Modules\App\Services\AppProgressManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AppController extends Controller
{
    protected AppProgressManager $progressManager;

    public function __construct(AppProgressManager $progressManager)
    {
        $this->progressManager = $progressManager;
    }

    public function index()
    {
        $apps = App::latest()->paginate(12);  // 最新順に12件ずつ取得
        return view('App::index', compact('apps'));
    }

    public function show(App $app)
    {
        // データベースから確実にデータを取得
        $app = App::with(['user'])->findOrFail($app->id);

        // Cloudinaryの画像URLを確実に取得
        $screenshots = [];
        if ($app->screenshots) {
            foreach ($app->screenshots as $screenshot) {
                $screenshots[] = [
                    'url' => $screenshot['url'] ?? '',
                    'public_id' => $screenshot['public_id'] ?? ''
                ];
            }
        }

        // その他の関連データを取得
        $app->load([
            'screenshots',
            'developmentStory',
            'hardware',
            'devEnvironment',
            'devTools',
            'architecture',
            'security',
            'backend',
            'frontend',
            'database'
        ]);

        // デバッグ用
        \Log::info('App data:', ['app' => $app->toArray()]);
        \Log::info('Screenshots:', ['screenshots' => $screenshots]);

        return view('App::show', [
            'app' => $app,
            'screenshots' => $screenshots,
            'appTypeLabels' => config('app.app_type_labels', []),
            'statusLabels' => config('app.status_labels', [])
        ]);
    }

    public function create(Request $request, string $section = 'basic-info')
    {
        // 基本情報が未完了の場合は、基本情報入力画面にリダイレクト
        if ($section !== 'basic-info' && !$this->isBasicInfoCompleted($request)) {
            return redirect()->route('app.create', ['section' => 'basic-info'])
                ->with('warning', '先に基本情報を入力してください。');
        }

        // セクションの存在確認
        $sections = $this->progressManager->getSections();
        if (!array_key_exists($section, $sections)) {
            return redirect()->route('app.create')
                ->with('error', '無効なセクションです。');
        }

        return view('app::app-form', [
            'currentSection' => $section,
            'sectionTitle' => $sections[$section]['title'],
            'sections' => $sections,
            'previousSection' => $this->progressManager->getPreviousSection($section),
            'nextSection' => $this->progressManager->getNextSection($section),
            'app' => new App(),
        ]);
    }

    private function isBasicInfoCompleted(Request $request): bool
    {
        $basicInfo = $request->session()->get('app_form.basic-info');
        return $basicInfo && isset($basicInfo['title'], $basicInfo['description']);
    }

    public function update(Request $request, $id)
    {
        $app = App::findOrFail($id);

        if ($app->user_id !== Auth::id()) {
            abort(403, '更新権限がありません。');
        }

        // 基本的な更新処理のみを残し、詳細はセクションコントローラーに移動
        $app->fill($request->validated());
        $app->save();

        return redirect()
            ->route('apps.show', $app)
            ->with('success', 'アプリを更新しました');
    }

    public function store(Request $request)
    {
        Log::info('Storing App Details:', [
            'app_type' => $request->input('app_type'),
            'all_inputs' => $request->all()  // 全入力値を出力
        ]);
        
        // ... 保存処理 ...
    }

    public function edit(App $app)
    {
        // アプリの詳細データを取得（リレーションも含めて）
        $app->load([
            'screenshots',
            'developmentStory',
            'hardware',
            'devEnvironment',
            'devTools',
            'architecture',
            'security',
            'backend',
            'frontend',
            'database'
        ]);

        $currentSection = session('current_section', 'basic-info');
        $sectionTitle = config('app.section_titles.' . $currentSection, '基本情報');

        return view('App::app-form', [
            'app' => $app,
            'currentSection' => $currentSection,
            'sectionTitle' => $sectionTitle,
            'appTypeLabels' => config('app.app_type_labels', []),
            'statusLabels' => config('app.status_labels', [])
        ]);
    }
} 