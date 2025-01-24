<x-app-layout>
    {{-- 超最先端のグラデーションヘッダー --}}
    <div x-data="appForm" class="min-h-screen bg-gradient-to-b from-gray-50 via-white to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            {{-- 豪華なタイトル部分 --}}
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600">
                    アプリ登録
                </h1>
                <p class="mt-3 text-xl text-gray-600">あなたの素晴らしいアプリケーションについて教えてください</p>
            </div>

            {{-- メインコンテンツ：ガラスモーフィズムデザイン --}}
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                {{-- タブナビゲーション：モダンなデザイン --}}
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8" aria-label="Tabs">
                        <template x-for="tab in tabs" :key="tab.id">
                            <button @click="switchTab(tab.id)"
                                    :class="{
                                        'border-blue-500 text-blue-600': activeTab === tab.id,
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== tab.id
                                    }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                    x-text="tab.name">
                            </button>
                        </template>
                    </nav>
                </div>

                {{-- タブコンテンツ：スムーズなトランジション --}}
                <div class="p-6">
                    <div x-show="activeTab === 'basic'" x-transition>
                        @include('AppV2::tabs._01_basic-tab')
                    </div>
                    <div x-show="activeTab === 'screenshots'" x-transition>
                        @include('AppV2::tabs._02_screenshots-tab')
                    </div>
                    <div x-show="activeTab === 'story'" x-transition>
                        @include('AppV2::tabs._03_story-tab')
                    </div>
                    <div x-show="activeTab === 'hardware'" x-transition>
                        @include('AppV2::tabs._04_hardware-tab')
                    </div>
                    <div x-show="activeTab === 'dev_env'" x-transition>
                        @include('AppV2::tabs._05_dev-env-tab')
                    </div>
                    <div x-show="activeTab === 'architecture'" x-transition>
                        @include('AppV2::tabs._06_architecture-tab')
                    </div>
                    <div x-show="activeTab === 'frontend'" x-transition>
                        @include('AppV2::tabs._07_frontend-tab')
                    </div>
                    <div x-show="activeTab === 'backend'" x-transition>
                        @include('AppV2::tabs._08_backend-tab')
                    </div>
                    <div x-show="activeTab === 'database'" x-transition>
                        @include('AppV2::tabs._09_database-tab')
                    </div>
                    <div x-show="activeTab === 'security'" x-transition>
                        @include('AppV2::tabs._10_security-tab')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 洗練された通知コンポーネント --}}
    @include('AppV2::components.autosave-notification')
    
    {{-- モダンなモーダル --}}
    @include('AppV2::components.screenshot-modal')

    {{-- Alpine.jsのデータ管理スクリプト --}}
    @include('AppV2::scripts.app-form')
</x-app-layout> 