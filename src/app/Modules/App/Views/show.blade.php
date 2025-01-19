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

                <!-- スクリーンショット表示部分 -->
                <div class="bg-white p-8 rounded-lg shadow">
                    <h2 class="text-2xl font-bold mb-6">スクリーンショット</h2>
                    
                    @if(is_array($app->screenshots) && !empty(array_filter($app->screenshots)))
                        <div class="grid gap-8">
                            @foreach(array_filter($app->screenshots) as $index => $screenshot)
                                @if(isset($screenshot['url']))
                                    <div class="relative w-full" x-data>
                                        <!-- スクリーンショットコンテナ -->
                                        <div class="flex justify-center items-center bg-gray-50 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300"
                                             @click="$dispatch('open-app-screenshot-modal', { src: '{{ $screenshot['url'] }}' })">
                                            
                                            <!-- スクリーンショット画像 -->
                                            <img src="{{ $screenshot['url'] }}" 
                                                 alt="アプリのスクリーンショット {{ $index + 1 }}" 
                                                 class="w-full h-auto cursor-pointer hover:opacity-95 transition-opacity duration-300"
                                                 style="max-height: 90vh; object-fit: contain;">
                                            
                                            <!-- オーバーレイ（ホバー時に表示） -->
                                            <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-opacity duration-300 flex items-center justify-center">
                                                <span class="text-white text-lg opacity-0 hover:opacity-100 transition-opacity duration-300">
                                                    クリックで拡大
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- スクリーンショット番号 -->
                                        <div class="absolute top-4 left-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
                                            {{ $index + 1 }}/{{ count(array_filter($app->screenshots, fn($s) => isset($s['url']))) }}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <p class="text-gray-500">スクリーンショットはまだ登録されていません。</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- スクリーンショットモーダル -->
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
        
        <!-- 背景オーバーレイ -->
        <div class="absolute inset-0 bg-black/75"
             x-show="show"
             x-transition.opacity
             @click="show = false">
        </div>

        <!-- モーダルコンテンツ -->
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
</body>
</html> 