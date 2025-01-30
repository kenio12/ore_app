@php
use App\Modules\AppV2\Models\App as AppModel;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ダッシュボード') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- ユーザーコントロールパネル -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- プロフィール管理 -->
                        <div class="p-4 border rounded-lg hover:bg-gray-50">
                            <a href="{{ route('profile.index') }}" class="block">
                                <h3 class="text-lg font-semibold mb-2">プロフィール設定</h3>
                                <p class="text-gray-600">プロフィール情報の確認・編集</p>
                            </a>
                        </div>

                        <!-- 新規アプリ投稿 -->
                        <div class="p-4 border rounded-lg hover:bg-gray-50">
                            <a href="{{ route('apps-v2.create') }}" 
                               onclick="clearAppFormData()"
                               class="block">
                                <h3 class="text-lg font-semibold mb-2">新規アプリ投稿</h3>
                                <p class="text-gray-600">新しいアプリを投稿する</p>
                            </a>
                        </div>

                        <!-- パスワード変更 -->
                        <div class="p-4 border rounded-lg hover:bg-gray-50">
                            <a href="{{ route('password.edit') }}" class="block">
                                <h3 class="text-lg font-semibold mb-2">パスワード変更</h3>
                                <p class="text-gray-600">アカウントのパスワードを変更する</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- アプリ一覧 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold mb-6">投稿したアプリ</h2>
                    
                    @if($apps->isEmpty())
                        <p class="text-gray-500 text-center">まだアプリを投稿していません。</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($apps as $app)
                                <div class="border rounded-lg p-4 hover:bg-gray-50 transition-all duration-200">
                                    @if($app->screenshots->isNotEmpty())
                                        <div class="flex justify-center items-center bg-gray-50 rounded-lg mb-4">
                                            <img 
                                                src="{{ $app->screenshots->first()->url }}"
                                                alt="{{ $app->title }}のスクリーンショット"
                                                class="max-w-full h-auto max-h-[200px] object-contain rounded-lg"
                                            >
                                        </div>
                                    @endif
                                    <h3 class="text-lg font-semibold mb-2">{{ $app->title }}</h3>
                                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $app->description }}</p>
                                    <a href="{{ route('apps-v2.show', $app) }}" 
                                       class="inline-flex items-center text-blue-600 hover:underline">
                                        詳細を見る
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- アカウント削除 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-lg font-medium text-red-600">
                        {{ __('アカウントの削除') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('アカウントを削除すると、すべてのデータが完全に削除されます。') }}
                    </p>
                    <form method="post" action="{{ route('profile.destroy') }}" class="mt-6">
                        @csrf
                        @method('delete')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('アカウントを削除') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
