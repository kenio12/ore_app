<nav class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="/" class="text-xl font-bold">
                        ORE APP
                    </a>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="space-x-4">
                    @auth
                        <!-- ログイン済みの場合 -->
                        <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
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
