<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- セクションナビゲーション -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold mb-4">
                    {{ $app->exists ? 'アプリ情報の編集' : '新規アプリの登録' }}
                </h1>
                <p class="text-gray-600">
                    現在のセクション: {{ $sectionTitle }}
                </p>
            </div>

            @include('App::Forms._form', [  // 大文字の'App::'
    'app' => $app,
    'currentSection' => $currentSection,
    'sections' => $sections ?? [],
    'previousSection' => $previousSection ?? null,
    'nextSection' => $nextSection ?? null
])
        </div>
    </div>
</x-app-layout> 