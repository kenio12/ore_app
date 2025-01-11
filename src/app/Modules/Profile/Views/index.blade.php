<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('プロフィール') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- プロフィール情報 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    ようこそ、{{ Auth::user()->name }}さん！
                </div>
            </div>

            <!-- パスワード変更セクション -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ __('パスワード設定') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('アカウントの安全性を保つため、長くて推測されにくいパスワードを使用してください。') }}
                    </p>
                    <div class="mt-4">
                        <x-primary-button onclick="location.href='{{ route('profile.password.change') }}'">
                            {{ __('パスワードを変更する') }}
                        </x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 