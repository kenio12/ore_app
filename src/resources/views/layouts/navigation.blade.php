<nav class="bg-white border-b border-gray-100">
    <div class="w-full px-6 sm:px-8 lg:px-10">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="/" class="text-xl font-bold px-2">
                        ORE APP
                    </a>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="flex sm:items-center sm:ms-6">
                <div class="space-x-4 px-2">
                    @auth
                        <!-- ログイン済みの場合 -->
                        <a href="{{ route('apps-v2.create') }}" 
                           onclick="clearAppFormData()"
                           class="text-sm text-gray-700 hover:text-gray-900">
                            アプリの新規投稿
                        </a>
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 hover:text-gray-900">
                            {{ Auth::user()->name }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-700 hover:text-gray-900">
                                ログアウト
                            </button>
                        </form>
                    @else
                        <!-- 未ログインの場合 -->
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">ログイン</a>
                        <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-gray-900">新規登録</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
function clearAppFormData() {
    // ローカルストレージのクリア
    localStorage.removeItem('appFormData');
    // セッションストレージのクリア
    sessionStorage.removeItem('appFormData');
}
</script>
