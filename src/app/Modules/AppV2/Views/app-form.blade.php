<x-app-layout>
    {{-- 超最先端のグラデーションヘッダー --}}
    <div x-data="appForm" class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-pink-50 py-12">
        {{-- app_idをここに追加 --}}
        <input type="hidden" name="app_id" value="{{ isset($app->id) ? $app->id : 'create' }}">

        {{-- 自動保存通知（直接インクルード） --}}
        @include('AppV2::components.autosave-notification')

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- 豪華なタイトル部分 --}}
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600">
                    アプリ登録
                </h1>
                <p class="mt-3 text-xl text-gray-600">あなたの素晴らしいアプリケーションについて教えてください</p>
            </div>

            {{-- タブナビゲーション（別コンポーネント） --}}
            @include('AppV2::components.tab-navigation')

            {{-- タブコンテンツ --}}
            <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl">
                <div x-show="activeTab === 'basic'" x-cloak>
                    @include('AppV2::tabs._01_basic-tab')
                </div>
                <div x-show="activeTab === 'screenshots'" x-cloak>
                    @include('AppV2::tabs._02_screenshots-tab')
                </div>
                <div x-show="activeTab === 'story'" x-cloak>
                    @include('AppV2::tabs._03_story-tab')
                </div>
                <div x-show="activeTab === 'hardware'" x-cloak>
                    @include('AppV2::tabs._04_hardware-tab')
                </div>
                <div x-show="activeTab === 'dev_env'" x-cloak>
                    @include('AppV2::tabs._05_dev-env-tab')
                </div>
                <div x-show="activeTab === 'architecture'" x-cloak>
                    @include('AppV2::tabs._06_architecture-tab')
                </div>
                <div x-show="activeTab === 'frontend'" x-cloak>
                    @include('AppV2::tabs._07_frontend-tab')
                </div>
                <div x-show="activeTab === 'backend'" x-cloak>
                    @include('AppV2::tabs._08_backend-tab')
                </div>
                <div x-show="activeTab === 'database'" x-cloak>
                    @include('AppV2::tabs._09_database-tab')
                </div>
                <div x-show="activeTab === 'security'" x-cloak>
                    @include('AppV2::tabs._10_security-tab')
                </div>
            </div>
        </div>
    </div>

    {{-- 洗練された通知コンポーネント --}}
    @include('AppV2::components.autosave-notification')
    
    {{-- モダンなモーダル --}}
    @include('AppV2::components.screenshot-modal')

    {{-- 自動保存のスクリプト --}}
    @include('AppV2::tabs.scripts.autosave')

    {{-- Alpine.js初期化スクリプト --}}
    @include('AppV2::scripts.app-form')
</x-app-layout> 