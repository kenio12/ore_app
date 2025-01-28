<x-app-layout>
    <!-- メインコンテナ -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
        <!-- ヒーローセクション -->
        <div x-data="{ showHero: true, showAppman: false }"
             x-init="setTimeout(() => {
                 showHero = false;
                 setTimeout(() => showAppman = true, 500);
             }, 10000)"
             class="relative">
            
            <!-- テキストヒーローセクション -->
            <div x-show="showHero"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-4"
                 class="relative text-center py-12 md:py-16 bg-gradient-to-r from-indigo-500 to-purple-600 mb-6 shadow-lg">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="relative z-10">
                    <h1 class="text-4xl md:text-6xl font-extrabold mb-4 text-white drop-shadow-lg">🗡️ 俺は 🏴‍☠️</h1>
                    <p class="text-xl md:text-2xl text-white drop-shadow-md">アプリになる！</p>
                </div>
            </div>

            <!-- アプリマンヒーローセクション -->
            <div x-show="showAppman"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="relative text-center py-12 md:py-16 mb-6 shadow-lg overflow-hidden"
                 style="background: radial-gradient(circle at center, #000B27 0%, #000000 100%);">

                <style>
                    .star {
                        position: absolute;
                        border-radius: 50%;
                        animation: twinkle 2s infinite;
                    }
                    @keyframes twinkle {
                        0% { opacity: 0.2; transform: scale(0.8); }
                        50% { opacity: 1; transform: scale(1.2); }
                        100% { opacity: 0.2; transform: scale(0.8); }
                    }
                    .star1 { width: 4px; height: 4px; left: 10%; top: 20%; background: #60A5FA; box-shadow: 0 0 10px #60A5FA; animation-delay: 0s; }
                    .star2 { width: 6px; height: 6px; right: 15%; top: 30%; background: #F472B6; box-shadow: 0 0 15px #F472B6; animation-delay: 0.3s; }
                    .star3 { width: 3px; height: 3px; left: 20%; bottom: 40%; background: #34D399; box-shadow: 0 0 8px #34D399; animation-delay: 0.6s; }
                    .star4 { width: 5px; height: 5px; right: 25%; bottom: 25%; background: #A78BFA; box-shadow: 0 0 12px #A78BFA; animation-delay: 0.9s; }
                    .star5 { width: 4px; height: 4px; left: 30%; top: 40%; background: #F472B6; box-shadow: 0 0 10px #F472B6; animation-delay: 1.2s; }
                    .star6 { width: 3px; height: 3px; right: 35%; top: 60%; background: #60A5FA; box-shadow: 0 0 8px #60A5FA; animation-delay: 1.5s; }
                    .star7 { width: 2px; height: 2px; left: 45%; top: 15%; background: #FCD34D; box-shadow: 0 0 10px #FCD34D; animation-delay: 1.8s; }
                    .star8 { width: 4px; height: 4px; right: 40%; bottom: 35%; background: #F87171; box-shadow: 0 0 12px #F87171; animation-delay: 2.1s; }
                    .star9 { width: 3px; height: 3px; left: 15%; top: 70%; background: #818CF8; box-shadow: 0 0 8px #818CF8; animation-delay: 2.4s; }
                    .star10 { width: 5px; height: 5px; right: 20%; top: 10%; background: #6EE7B7; box-shadow: 0 0 15px #6EE7B7; animation-delay: 2.7s; }
                    /* 新しい星を追加 */
                    .star11 { width: 3px; height: 3px; left: 8%; top: 45%; background: #F59E0B; box-shadow: 0 0 10px #F59E0B; animation-delay: 3.0s; }
                    .star12 { width: 4px; height: 4px; right: 12%; top: 55%; background: #EC4899; box-shadow: 0 0 12px #EC4899; animation-delay: 3.3s; }
                    .star13 { width: 2px; height: 2px; left: 25%; top: 8%; background: #10B981; box-shadow: 0 0 8px #10B981; animation-delay: 3.6s; }
                    .star14 { width: 5px; height: 5px; right: 28%; top: 75%; background: #8B5CF6; box-shadow: 0 0 15px #8B5CF6; animation-delay: 3.9s; }
                    .star15 { width: 3px; height: 3px; left: 38%; bottom: 15%; background: #3B82F6; box-shadow: 0 0 10px #3B82F6; animation-delay: 4.2s; }
                    .star16 { width: 4px; height: 4px; right: 33%; top: 5%; background: #EF4444; box-shadow: 0 0 12px #EF4444; animation-delay: 4.5s; }
                    .star17 { width: 2px; height: 2px; left: 42%; bottom: 8%; background: #14B8A6; box-shadow: 0 0 8px #14B8A6; animation-delay: 4.8s; }
                    .star18 { width: 3px; height: 3px; right: 45%; top: 82%; background: #6366F1; box-shadow: 0 0 10px #6366F1; animation-delay: 5.1s; }
                    .star19 { width: 4px; height: 4px; left: 18%; bottom: 22%; background: #F472B6; box-shadow: 0 0 12px #F472B6; animation-delay: 5.4s; }
                    .star20 { width: 3px; height: 3px; right: 22%; bottom: 18%; background: #2DD4BF; box-shadow: 0 0 10px #2DD4BF; animation-delay: 5.7s; }

                    /* 放射状の光のアニメーション */
                    @keyframes radiate {
                        0% { transform: scale(1); opacity: 0.5; }
                        100% { transform: scale(1.5); opacity: 0; }
                    }
                    .radiation {
                        position: absolute;
                        inset: -50%;
                        background: radial-gradient(circle at center, 
                            rgba(0,255,255,0.2) 0%,
                            rgba(0,149,255,0.1) 30%,
                            rgba(255,0,255,0.05) 50%,
                            transparent 70%);
                        animation: radiate 3s infinite;
                    }
                    .radiation2 {
                        animation-delay: 1.5s;
                    }
                </style>

                <!-- 星々 -->
                <div class="star star1"></div>
                <div class="star star2"></div>
                <div class="star star3"></div>
                <div class="star star4"></div>
                <div class="star star5"></div>
                <div class="star star6"></div>
                <div class="star star7"></div>
                <div class="star star8"></div>
                <div class="star star9"></div>
                <div class="star star10"></div>
                <!-- 新しい星を追加 -->
                <div class="star star11"></div>
                <div class="star star12"></div>
                <div class="star star13"></div>
                <div class="star star14"></div>
                <div class="star star15"></div>
                <div class="star star16"></div>
                <div class="star star17"></div>
                <div class="star star18"></div>
                <div class="star star19"></div>
                <div class="star star20"></div>

                <!-- サイバーな光の効果 -->
                <div class="absolute inset-0" 
                     style="background: radial-gradient(circle at center, rgba(0,149,255,0.15) 0%, rgba(0,149,255,0) 70%);"></div>
                <div class="absolute inset-0" 
                     style="background: radial-gradient(circle at center, rgba(255,0,255,0.1) 0%, rgba(255,0,255,0) 60%);"></div>

                <!-- コンテンツ -->
                <div class="relative z-10 flex flex-col justify-center items-center">
                    <div class="w-[30vw] md:w-[18vw] mx-auto relative">
                        <!-- 放射状の光のエフェクト -->
                        <div class="radiation"></div>
                        <div class="radiation radiation2"></div>
                        <!-- サイバーな光の輪 -->
                        <div class="absolute inset-0"
                             style="background: radial-gradient(circle at center, rgba(0,255,255,0.2) 0%, transparent 70%);"></div>
                        <img src="{{ asset('images/appman.png') }}" 
                             alt="アプリマン" 
                             class="w-full h-auto object-contain drop-shadow-lg hover:scale-110 transition-transform duration-300 origin-top relative z-10"
                             style="max-width: 300px; filter: drop-shadow(0 0 10px rgba(0,255,255,0.3));">
                    </div>
                    <p class="mt-4 text-xl font-bold text-white" 
                       style="text-shadow: 0 0 10px rgba(0,255,255,0.5);">オレ、アプリマンだ！</p>
                </div>
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
                                            {{ config('app-module.constants')['app_types'][$appType] ?? $appType }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- アプリヘッダー -->
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-2xl font-semibold">{{ $app->title }}</h3>
                                <span class="px-3 py-1 rounded text-sm font-medium text-white"
                                    style="background-color: {{ \App\Modules\App\Helpers\ColorHelper::getStatusColor($app->app_status) }}">
                                    {{ config('app-module.constants')['app_status'][$app->app_status] ?? $app->app_status }}
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