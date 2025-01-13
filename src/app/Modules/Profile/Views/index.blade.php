<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('プロフィール') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- プロフィール情報 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('プロフィール情報') }}
                    </h2>
                    <!-- プロフィール情報の表示 -->
                </div>
            </div>

            <!-- アプリ一覧（apps.indexと同じレイアウト） -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($apps as $app)
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                @if($app->screenshot_url)
                                    <img 
                                        src="{{ $app->screenshot_url }}" 
                                        alt="{{ $app->name }}のスクリーンショット"
                                        class="w-full h-48 object-cover rounded-lg mb-4"
                                    >
                                @endif
                                <h3 class="text-lg font-semibold mb-2">{{ $app->name }}</h3>
                                <p class="text-gray-600 mb-4">{{ $app->description }}</p>
                                <a href="{{ route('apps.show', $app) }}" class="text-blue-600 hover:underline">
                                    詳細を見る →
                                </a>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8 text-gray-600">
                                まだアプリを投稿していません。
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 