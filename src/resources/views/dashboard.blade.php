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

                        <!-- パスワード変更 -->
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

                    <!-- 危険な操作エリア -->
                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">危険な操作</h3>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <form method="POST" action="{{ route('profile.destroy') }}" class="flex items-center justify-between">
                                @csrf
                                @method('DELETE')
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">アカウント削除</h4>
                                    <p class="text-gray-600">一度削除すると、全てのデータが完全に削除され、復元できません。</p>
                                </div>
                                <button type="submit" 
                                    class="bg-red-100 text-red-600 border border-red-200 px-4 py-2 rounded-lg hover:bg-red-200 transition-colors duration-200"
                                    onclick="return confirm('本当にアカウントを削除しますか？この操作は取り消せません。')">
                                    アカウントを削除する
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
