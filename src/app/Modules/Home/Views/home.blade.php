<x-app-layout>
    <!-- メインコンテナ -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
        <!-- ヒーローセクション -->
        <div class="relative text-center py-12 md:py-16 bg-gradient-to-r from-indigo-500 to-purple-600 mb-12 shadow-lg">
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
            <!-- アプリグリッド -->
            <div class="space-y-8 mb-12">
                @forelse($apps as $app)
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-200 hover:-translate-y-1 overflow-hidden">
                        <div class="p-6">
                            <!-- バッジエリア -->
                            <div class="flex justify-between items-center mb-3">
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium text-white"
                                        style="background-color: {{ $appTypeColors[$app->app_type] ?? '#9CA3AF' }}">
                                        {{ $appTypeLabels[$app->app_type] ?? 'その他' }}
                                    </span>
                                </div>
                            </div>

                            <!-- アプリヘッダー -->
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-2xl font-semibold">{{ $app->title }}</h3>
                                <span class="px-3 py-1 rounded text-sm font-medium text-white
                                    {{ $app->status === 'completed' ? 'bg-emerald-600' : 'bg-amber-600' }}">
                                    {{ $statusLabels[$app->status] ?? '開発中' }}
                                </span>
                            </div>

                            <!-- スクリーンショット -->
                            <div class="bg-gray-50 flex justify-center items-center mb-4">
                                @if($app->screenshots && count($app->screenshots) > 0)
                                    <a href="{{ $app->screenshots[0] }}" target="_blank" rel="noopener noreferrer">
                                        <img 
                                            class="object-contain w-auto cursor-pointer hover:opacity-90 transition-opacity"
                                            style="max-height: 330px;"
                                            src="{{ $app->screenshots[0] }}"
                                            alt="{{ $app->title }}"
                                            onerror="this.src='/default-app-image.png'"
                                        >
                                    </a>
                                @endif
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

            <!-- アカウント操作 -->
            @auth
                <div class="text-center mb-8">
                    <form action="{{ route('profile.destroy') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-6 py-3 border border-red-500 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200"
                            onclick="return confirm('本当にアカウントを削除しますか？\n\n⚠️ この操作は取り消せません。\n- すべての投稿が削除されます\n- アカウント情報が完全に削除されます')">
                            退会する
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </div>

    <!-- モーダルコンポーネントを追加 -->
    <x-app::app-screenshot-modal />
</x-app-layout> 