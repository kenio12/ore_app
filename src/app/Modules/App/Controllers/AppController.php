<?php

namespace App\Modules\App\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\App\Models\App;
use App\Modules\App\Services\AppProgressManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Modules\App\Requests\_01BasicInfoRequest;
use Illuminate\Support\Facades\DB;

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

    public function show($id)
    {
        try {
            $app = App::findOrFail($id);
            
            // ここでデータの中身を確認
            Log::debug('取得したデータ:', [
                'app_id' => $app->id,
                'title' => $app->title,
                'description' => $app->description,
                'publication_status' => $app->publication_status,
                'app_url' => $app->app_url,
                'github_url' => $app->github_url,
                'app_status' => $app->app_status,
                'development_start' => $app->development_start,
                'development_end' => $app->development_end,
                'app_types' => $app->app_types,
                'genres' => $app->genres
            ]);

            return view('App::show', [
                'app' => $app
            ]);

        } catch (\Exception $e) {
            Log::error('エラー発生:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            throw $e;
        }
    }

    private function formatBytes($bytes)
    {
        if ($bytes > 1024*1024) {
            return round($bytes/1024/1024, 2) . ' MB';
        } elseif ($bytes > 1024) {
            return round($bytes/1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }

    public function create()
    {
        $app = new App();
        $progressManager = app(AppProgressManager::class);
        $currentSection = $progressManager->getCurrentSection();
        
        return view('App::app-form', [
            'app' => $app,
            'currentSection' => $currentSection,
            'sections' => $progressManager->getSections(),
            'sectionTitle' => $progressManager->getSections()[$currentSection]['title'],
            'previousSection' => $progressManager->getPreviousSection($currentSection),
            'nextSection' => $progressManager->getNextSection($currentSection)
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

    public function edit(App $app)
    {
        $progressManager = app(AppProgressManager::class);
        $currentSection = $progressManager->getCurrentSection();
        
        return view('App::app-form', [
            'app' => $app,
            'currentSection' => $currentSection,
            'sections' => $progressManager->getSections(),
            'sectionTitle' => $progressManager->getSections()[$currentSection]['title'],
            'previousSection' => $progressManager->getPreviousSection($currentSection),
            'nextSection' => $progressManager->getNextSection($currentSection),
            'viewOnly' => false
        ]);
    }

    // ダッシュボード用のメソッド
    public function dashboard()
    {
        // SQL文を直接確認するためにtoSqlを使用してデバッグ
        \Log::info(
            App::query()
                ->where('user_id', auth()->id())
                ->orderByDesc('created_at')
                ->toSql()
        );

        // 確実に降順になるように明示的に指定
        $apps = App::query()
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'DESC')  // 明示的にDESCを指定
            ->get();

        // コレクションを確実に逆順にする
        $apps = $apps->sortByDesc('created_at')->values();

        return view('dashboard', compact('apps'));
    }

    public function store(_01BasicInfoRequest $request)
    {
        // ...
    }
} 