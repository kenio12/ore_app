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
            <div class="space-y-6">
                {{-- 編集ボタンを中央に配置 --}}
                @if(auth()->id() === $app->user_id)
                    <div class="flex justify-center mb-4">
                        <a href="{{ route('apps.edit', ['app' => $app->id]) }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            このアプリを編集する
                        </a>
                    </div>
                @endif

                <form onsubmit="return false;" class="pointer-events-none">
                    {{-- 基本情報フォーム --}}
                    @include('App::Forms.01_BasicInfoForm', ['app' => $app, 'viewOnly' => true])
                    {{-- @include('App::Forms.02_DevelopmentStoryForm', ['app' => $app, 'viewOnly' => true]) --}}
                    {{-- @include('App::Forms.03_HardwareSection', ['app' => $app, 'viewOnly' => true]) --}}
                    {{-- @include('App::Forms.04_BasicDevSection', ['app' => $app, 'viewOnly' => true]) --}}
                    {{-- @include('App::Forms.05_DevToolsEnvironment', ['app' => $app, 'viewOnly' => true]) --}}
                    {{-- @include('App::Forms.06_ArchitectureSection', ['app' => $app, 'viewOnly' => true]) --}}
                    {{-- @include('App::Forms.07_SecuritySection', ['app' => $app, 'viewOnly' => true]) --}}
                    {{-- @include('App::Forms.08_BackendSection', ['app' => $app, 'viewOnly' => true]) --}}
                    {{-- @include('App::Forms.09_FrontendSection', ['app' => $app, 'viewOnly' => true]) --}}
                    {{-- @include('App::Forms.10_DatabaseSection', ['app' => $app, 'viewOnly' => true]) --}}
                </form>
            </div>
        </div>
    </div>

    {{-- モーダルは残す（フォームコンポーネントから使用される） --}}
    <div x-data="{ show: false }"
         x-on:open-app-screenshot-modal.window="
            show = true;
            $nextTick(() => {
                $refs.modalImage.src = $event.detail.src;
            });"
         x-on:keydown.escape.window="show = false"
         x-show="show"
         class="fixed inset-0 z-50 overflow-hidden"
         style="display: none;">
        <div class="absolute inset-0 bg-black/75"
             x-show="show"
             x-transition.opacity
             @click="show = false">
        </div>
        <div class="relative h-full w-full flex items-center justify-center p-8">
            <img x-ref="modalImage"
                 class="rounded-lg shadow-xl"
                 style="max-width: 95vw; max-height: 95vh; width: auto; height: auto;"
                 alt="スクリーンショット">
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

    {{-- 画像モーダル用のスクリプト --}}
    <script>
        function openImageModal(src) {
            // Alpine.jsのイベントを発火
            window.dispatchEvent(new CustomEvent('open-app-screenshot-modal', {
                detail: { src: src }
            }));
        }
    </script>
</body>
</html> 