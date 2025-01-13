<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('プロフィール') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- プロフィール情報 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <div class="p-6 text-gray-900">
                    ようこそ、{{ Auth::user()->name }}さん！
                </div>
            </div>

            <!-- 作成したアプリ一覧 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('作成したアプリ') }}
                    </h3>
                    
                    <div class="space-y-6">
                        @forelse($apps as $app)
                            <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-all duration-200">
                                <!-- スクリーンショット（装飾なし） -->
                                @if($app->screenshots && count($app->screenshots) > 0)
                                    <div class="mb-3">
                                        <img src="{{ $app->screenshots[0] }}"
                                            alt="{{ $app->title }}"
                                            onerror="this.src='/default-app-image.png'">
                                    </div>
                                @endif

                                <!-- その他の情報（元の装飾を維持） -->
                                <div class="p-6 text-gray-900">
                                    <h4 class="text-xl font-bold">{{ $app->title }}</h4>
                                    <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                        {{ $statusLabels[$app->status] ?? '開発中' }}
                                    </span>
                                    <div class="mt-2 text-gray-600">
                                        作成日: {{ $app->created_at->format('Y年n月j日') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-600">まだアプリを作成していません。</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- スクリーンショットモーダル -->
    <x-app::app-screenshot-modal />
</x-app-layout> 