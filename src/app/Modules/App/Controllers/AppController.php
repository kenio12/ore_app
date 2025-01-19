<?php

namespace App\Modules\App\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\App\Models\App;
use App\Modules\App\Services\AppProgressManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Modules\App\Requests\BasicInfoRequest;  // 既存のRequestを使用

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
        // リレーションは不要。直接モデルのデータを使用
        return view('App::show', [
            'app' => $app,
            'viewOnly' => true
        ]);
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

    public function store(BasicInfoRequest $request)
    {
        Log::info('Storing App Details:', [
            'app_type' => $request->input('app_type'),
        ]);

        try {
            // BasicInfoRequestで既にバリデーション済みのデータを使用
            $validatedData = $request->validated();
            
            // セッションに保存
            $request->session()->put('app_form.basic-info', $validatedData);

            // 次のセクションへリダイレクト
            $nextSection = $this->progressManager->getNextSection('basic-info');
            if ($nextSection) {
                return redirect()->route('app.sections.' . $nextSection, [
                    'section' => $nextSection
                ])->with('success', '基本情報を保存しました。次のセクションに進みます。');
            }

            return back()
                ->withInput()
                ->withErrors(['error' => '次のセクションが見つかりませんでした。']);

        } catch (\Exception $e) {
            Log::error('App creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'アプリの登録に失敗しました：' . $e->getMessage()]);
        }
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