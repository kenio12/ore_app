<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- セクションナビゲーション -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold mb-4">
                    {{ $app->exists ? 'アプリ情報の編集' : '新規アプリの登録' }}
                </h1>
                <div class="text-sm breadcrumbs">
                    <ul>
                        <li><a href="{{ route('apps.index') }}">アプリ一覧</a></li>
                        <li>{{ $sectionTitle }}</li>
                    </ul>
                </div>
            </div>

            <!-- メインフォーム -->
            @include('App::Forms._form', [
                'app' => $app,
                'currentSection' => $currentSection,
                'sections' => $sections,
                'sectionTitle' => $sectionTitle,
                'previousSection' => $previousSection,
                'nextSection' => $nextSection
            ])
        </div>
    </div>
</x-app-layout> 