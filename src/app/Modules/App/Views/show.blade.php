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
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                <form onsubmit="return false;" class="pointer-events-none">
                    {{-- 各セクションを読み取り専用で表示 --}}
                    @include('App::Forms.01_BasicInfoForm', ['app' => $app, 'viewOnly' => true])
                    @include('App::Forms.02_DevelopmentStoryForm', ['app' => $app, 'viewOnly' => true])
                    @include('App::Forms.03_HardwareSection', ['app' => $app, 'viewOnly' => true])
                    @include('App::Forms.04_BasicDevEnvironment', ['app' => $app, 'viewOnly' => true])
                    @include('App::Forms.05_DevToolsEnvironment', ['app' => $app, 'viewOnly' => true])
                    @include('App::Forms.06_ArchitectureSection', ['app' => $app, 'viewOnly' => true])
                    @include('App::Forms.07_SecuritySection', ['app' => $app, 'viewOnly' => true])
                    @include('App::Forms.08_BackendSection', ['app' => $app, 'viewOnly' => true])
                    @include('App::Forms.09_FrontendSection', ['app' => $app, 'viewOnly' => true])
                    @include('App::Forms.10_DatabaseSection', ['app' => $app, 'viewOnly' => true])
                </form>
            </div>
        </div>
    </div>

    <style>
        /* フォーム全体を読み取り専用にするスタイル */
        .pointer-events-none input,
        .pointer-events-none select,
        .pointer-events-none textarea,
        .pointer-events-none button {
            pointer-events: none;
            background-color: #f3f4f6;
            border-color: #e5e7eb;
            cursor: default;
        }
    </style>
</body>
</html> 