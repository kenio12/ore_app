<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $app->title ?? 'アプリ詳細' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
    @include('layouts.navigation')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 編集ボタン -->
            @if(auth()->id() === $app->user_id)
                <div class="flex justify-center mb-6">
                    <a href="{{ route('apps.edit', $app) }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        編集する
                    </a>
                </div>
            @endif

            <!-- 表示専用フォーム -->
            <div class="space-y-6">
                @include('App::Forms.01_BasicInfoForm', ['app' => $app, 'viewOnly' => true])
                @include('App::Forms.02_DevelopmentStoryForm', ['app' => $app, 'viewOnly' => true])
                @include('App::Forms.03_Hardware', ['app' => $app, 'viewOnly' => true])
                @include('App::Forms.04_BasicDevSection', ['app' => $app, 'viewOnly' => true])
                @include('App::Forms.05_DevToolsEnvironment', ['app' => $app, 'viewOnly' => true])
                @include('App::Forms.06_ArchitectureSection', ['app' => $app, 'viewOnly' => true])
                @include('App::Forms.07_SecuritySection', ['app' => $app, 'viewOnly' => true])
                @include('App::Forms.08_BackendSection', ['app' => $app, 'viewOnly' => true])
                @include('App::Forms.09_FrontendSection', ['app' => $app, 'viewOnly' => true])
                @include('App::Forms.10_DatabaseSection', ['app' => $app, 'viewOnly' => true])
            </div>
        </div>
    </div>

    <style>
        /* 表示専用スタイル */
        input[type="text"],
        input[type="radio"],
        input[type="checkbox"],
        textarea,
        select {
            pointer-events: none;
            background-color: #f3f4f6 !important;
            border-color: #e5e7eb !important;
            opacity: 0.75;
        }

        /* スクリーンショット画像のスタイル */
        .screenshot-image {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        }
    </style>
</body>
</html> 