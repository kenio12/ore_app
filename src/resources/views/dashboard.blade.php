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
                            <a href="{{ route('apps.create') }}" class="block">
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
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('投稿したアプリ') }}
                    </h2>
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
