@php
use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            アプリ一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- アプリ一覧のグリッド -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($apps ?? [] as $app)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                <!-- スクリーンショット -->
                                @if($app->screenshots && count($app->screenshots) > 0)
                                    <img src="{{ $app->screenshots[0]['url'] }}" 
                                         alt="{{ $app->title }}" 
                                         class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400">No Image</span>
                                    </div>
                                @endif

                                <div class="p-4">
                                    <h3 class="text-lg font-semibold mb-2">{{ $app->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-4">
                                        {{ Str::limit($app->description, 100) }}
                                    </p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500">
                                            {{ $app->created_at->format('Y/m/d') }}
                                        </span>
                                        <a href="{{ route('apps.show', $app) }}" 
                                           class="text-blue-600 hover:text-blue-800">
                                            詳細を見る
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="col-span-3 text-center text-gray-500 py-8">
                                まだアプリが登録されていません。
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 