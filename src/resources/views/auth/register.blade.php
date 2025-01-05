<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-12">
        <div class="max-w-md mx-auto bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">新規登録</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('ユーザー名')" />
                    <x-text-input id="name" 
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" 
                        type="text" 
                        name="name" 
                        :value="old('name')" 
                        required 
                        autofocus 
                        autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('メールアドレス')" />
                    <x-text-input id="email" 
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('パスワード')" />
                    <x-text-input id="password" 
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        type="password"
                        name="password"
                        required 
                        autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('パスワード（確認用）')" />
                    <x-text-input id="password_confirmation" 
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        type="password"
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a class="text-sm text-indigo-600 hover:text-indigo-800 transition-colors duration-200" 
                        href="{{ route('login') }}">
                        すでにアカウントをお持ちの方
                    </a>

                    <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">
                        新規登録
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
