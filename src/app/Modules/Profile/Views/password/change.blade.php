<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('パスワード変更') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.password.update') }}">
                        @csrf

                        <!-- Current Password -->
                        <div>
                            <x-input-label for="current_password" :value="__('現在のパスワード')" />
                            <x-text-input id="current_password" class="block mt-1 w-full"
                                type="password"
                                name="current_password"
                                required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                        </div>

                        <!-- New Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('新しいパスワード')" />
                            <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('新しいパスワード（確認用）')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-secondary-button class="mr-3" onclick="location.href='{{ route('profile.index') }}'">
                                {{ __('キャンセル') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('パスワードを変更する') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 