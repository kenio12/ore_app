<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ヘッダー -->
            <div class="mb-8 flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">
                    アプリ一覧
                </h1>
                <a 
                    href="{{ route('app-posts.create') }}" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    新規投稿
                </a>
            </div>

            <!-- フィルター -->
            <div class="bg-white p-4 rounded-lg shadow mb-8">
                <form action="{{ route('app-posts.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <!-- アプリタイプ -->
                    <div class="flex-1 min-w-[200px]">
                        <select 
                            name="app_type" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">アプリタイプ</option>
                            @foreach($appTypeLabels as $value => $label)
                                <option 
                                    value="{{ $value }}"
                                    {{ request('app_type') == $value ? 'selected' : '' }}
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 開発状況 -->
                    <div class="flex-1 min-w-[200px]">
                        <select 
                            name="status" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">開発状況</option>
                            @foreach($statusLabels as $value => $label)
                                <option 
                                    value="{{ $value }}"
                                    {{ request('status') == $value ? 'selected' : '' }}
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 検索ボタン -->
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2"
                    >
                        検索
                    </button>
                </form>
            </div>

            <!-- アプリ一覧 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($apps as $app)
                    @include('app-posts.cards.app-post-card', ['app' => $app])
                @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-lg shadow">
                        <p class="text-gray-500">アプリの投稿がありません</p>
                    </div>
                @endforelse
            </div>

            <!-- ページネーション -->
            <div class="mt-8">
                {{ $apps->links() }}
            </div>
        </div>
    </div>
</x-app-layout> 