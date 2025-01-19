<x-app-layout>
    <!-- メインコンテナ -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
        <!-- ヒーローセクション -->
        <div 
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 10000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-4"
            class="relative text-center py-12 md:py-16 bg-gradient-to-r from-indigo-500 to-purple-600 mb-6 shadow-lg"
        >
            <div class="absolute inset-0 bg-black/20"></div>
            <div class="relative z-10">
                <h1 class="text-4xl md:text-6xl font-extrabold mb-4 text-white drop-shadow-lg">🗡️ 俺は 🏴‍☠️</h1>
                <p class="text-xl md:text-2xl text-white drop-shadow-md">アプリになる！</p>
            </div>
        </div>

        <!-- フラッシュメッセージ -->
        @if (session('status'))
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <!-- コンテンツコンテナ -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- 上部スペーサー（ヒーローセクションの表示状態に応じて調整） -->
            <div 
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 10000)"
                class="transition-all duration-300"
                :class="{ 'h-8 md:h-12': !show, 'h-0': show }"
            ></div>

            <!-- アプリグリッド -->
            <div class="space-y-8 mb-12">
                @forelse($apps as $app)
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-200 hover:-translate-y-1 overflow-hidden">
                        <div class="p-6">
                            <!-- バッジエリア -->
                            <div class="flex justify-between items-center mb-3">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($app->app_types as $appType)
                                        <span class="px-3 py-1 rounded-full text-sm font-medium text-white"
                                            style="background-color: {{ \App\Modules\App\Helpers\ColorHelper::getAppTypeColor($appType) }}">
                                            {{ $appTypeLabels[$appType] ?? 'その他' }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- アプリヘッダー -->
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-2xl font-semibold">{{ $app->title }}</h3>
                                <span class="px-3 py-1 rounded text-sm font-medium text-white"
                                    style="background-color: {{ \App\Modules\App\Helpers\ColorHelper::getStatusColor($app->status) }}">
                                    {{ $statusLabels[$app->status] ?? '下書き' }}
                                </span>
                            </div>

                            <!-- スクリーンショット -->
                            <div class="bg-gray-50 flex justify-center items-center mb-4" x-data>
                                @if(is_array($app->screenshots) && !empty($app->screenshots) && isset($app->screenshots[0]['url']))
                                    <img 
                                        class="object-contain w-auto cursor-zoom-in hover:opacity-90 transition-opacity"
                                        style="max-height: 330px;"
                                        src="{{ $app->screenshots[0]['url'] }}"
                                        alt="{{ $app->title }}"
                                        @click="$dispatch('open-app-screenshot-modal', { src: '{{ $app->screenshots[0]['url'] }}' })"
                                    >
                                @else
                                    <div class="bg-gray-100 p-4 rounded-lg text-gray-500 flex items-center justify-center" style="height: 330px;">
                                        <span>画像なし</span>
                                    </div>
                                @endif
                            </div>

                            <!-- 詳細ボタンを中央配置 -->
                            <div class="flex justify-center mb-4">
                                <a href="{{ route('apps.show', $app) }}" 
                                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-wider hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <span class="mr-2">👀</span>
                                    詳細を見る
                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>

                            <!-- メタ情報 -->
                            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm"
                                        style="background-color: {{ \App\Modules\App\Helpers\ColorHelper::generateColorFromString($app->user->name) }}">
                                        {{ substr($app->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-gray-700">{{ $app->user->name }}</span>
                                </div>
                                <div class="text-sm text-gray-500">{{ $app->created_at->format('Y年n月j日') }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500">まだアプリがありません。</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- モーダル部分を追加 -->
    <div
        x-data="{ 
            show: false,
            imageSrc: '',
            init() {
                window.addEventListener('open-app-screenshot-modal', (e) => {
                    this.imageSrc = e.detail.src;
                    this.show = true;
                });
            }
        }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-hidden"
        style="background-color: rgba(0, 0, 0, 0.75);"
        @click.self="show = false"
        @keydown.escape.window="show = false"
    >
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative">
                <!-- 閉じるボタン -->
                <button 
                    @click="show = false"
                    class="absolute top-4 right-4 bg-red-500 hover:bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center shadow-lg transition-all duration-200 transform hover:scale-110"
                >
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path 
                            stroke-linecap="round" 
                            stroke-linejoin="round" 
                            stroke-width="2.5" 
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>

                <!-- 画像 -->
                <img
                    :src="imageSrc"
                    class="max-w-[90vw] max-h-[90vh] object-contain rounded-lg shadow-2xl"
                    alt="拡大画像"
                >
            </div>
        </div>
    </div>
</x-app-layout> 