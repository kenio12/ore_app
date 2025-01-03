<div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
    <!-- カードヘッダー -->
    <div class="relative">
        <!-- スクリーンショット -->
        <div class="aspect-w-16 aspect-h-9 bg-gray-100">
            @if($app->screenshots && count($app->screenshots) > 0)
                <img 
                    src="{{ $app->screenshots[0] }}" 
                    alt="{{ $app->title }}"
                    class="object-cover w-full h-full"
                    onerror="this.src='/default-app-image.png'"
                >
            @else
                <div class="flex items-center justify-center h-full bg-gray-100">
                    <span class="text-gray-400">No Screenshot</span>
                </div>
            @endif
        </div>

        <!-- アプリタイプバッジ -->
        <div class="absolute top-2 left-2 flex flex-wrap gap-1">
            @foreach($app->app_types as $type)
                <span 
                    class="px-2 py-1 text-xs font-medium rounded-full"
                    style="background-color: {{ $appTypeColors[$type] ?? '#9CA3AF' }}; color: white;"
                >
                    {{ $appTypeLabels[$type] ?? 'その他' }}
                </span>
            @endforeach
        </div>

        <!-- ステータスバッジ -->
        <div class="absolute top-2 right-2">
            <span class="px-2 py-1 text-xs font-medium rounded-full bg-opacity-90 {{ $app->status === 'completed' ? 'bg-green-500' : 'bg-blue-500' }} text-white">
                {{ $statusLabels[$app->status] ?? '開発中' }}
            </span>
        </div>
    </div>

    <!-- カードコンテンツ -->
    <div class="p-4">
        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $app->title }}</h3>
        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $app->description }}</p>

        <!-- 投稿者情報 -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div 
                    class="w-8 h-8 rounded-full flex items-center justify-center text-white font-medium"
                    style="background-color: {{ generateColorFromString($app->user->name) }}"
                >
                    {{ substr($app->user->name, 0, 1) }}
                </div>
                <span class="text-sm text-gray-600">{{ $app->user->name }}</span>
            </div>
            <span class="text-xs text-gray-500">
                {{ $app->created_at->format('Y.m.d') }}
            </span>
        </div>
    </div>

    <!-- カードフッター -->
    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
        <a 
            href="{{ route('app-posts.show', $app) }}" 
            class="text-blue-600 hover:text-blue-800 text-sm font-medium"
        >
            詳細を見る →
        </a>
    </div>
</div> 