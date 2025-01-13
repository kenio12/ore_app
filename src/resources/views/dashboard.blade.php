<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ダッシュボード') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- ユーザーコントロールパネル -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- プロフィール管理 -->
                        <div class="p-4 border rounded-lg hover:bg-gray-50">
                            <a href="{{ route('profile.index') }}" class="block">
                                <h3 class="text-lg font-semibold mb-2">プロフィール設定</h3>
                                <p class="text-gray-600">プロフィール情報の確認・編集</p>
                            </a>
                        </div>

                        <!-- パスワード変更 - 新規追加 -->
                        <div class="p-4 border rounded-lg hover:bg-gray-50">
                            <a href="{{ route('password.edit') }}" class="block">
                                <h3 class="text-lg font-semibold mb-2">パスワード変更</h3>
                                <p class="text-gray-600">アカウントのパスワードを変更</p>
                            </a>
                        </div>

                        <!-- 投稿したアプリ一覧 -->
                        <div class="p-4 border rounded-lg hover:bg-gray-50">
                            <a href="{{ route('apps.index') }}" class="block">
                                <h3 class="text-lg font-semibold mb-2">投稿したアプリ</h3>
                                <p class="text-gray-600">あなたが投稿したアプリの管理</p>
                            </a>
                        </div>

                        <!-- 新規アプリ投稿 -->
                        <div class="p-4 border rounded-lg hover:bg-gray-50">
                            <a href="{{ route('apps.create') }}" class="block">
                                <h3 class="text-lg font-semibold mb-2">新規アプリ投稿</h3>
                                <p class="text-gray-600">新しいアプリを投稿する</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
