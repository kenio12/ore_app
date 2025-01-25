<x-app-layout>
    {{-- 超最先端のグラデーションヘッダー --}}
    <div x-data="formData" class="min-h-screen bg-gradient-to-b from-gray-50 via-white to-blue-50 p-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            {{-- 豪華なタイトル部分 --}}
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600">
                    アプリ登録
                </h1>
                <p class="mt-3 text-xl text-gray-600">あなたの素晴らしいアプリケーションについて教えてください</p>
            </div>

            {{-- メインコンテンツ：ガラスモーフィズムデザイン --}}
            <div class="bg-white/50 backdrop-blur-lg rounded-xl p-4 shadow-lg">
                <nav class="flex flex-wrap gap-2">
                    @foreach(config('appv2.constants.tabs') as $tabKey => $tab)
                        <button
                            @click="activeTab = '{{ $tabKey }}'"
                            :class="{
                                'bg-blue-500 text-white ring-2 ring-blue-300': activeTab === '{{ $tabKey }}',
                                'bg-white text-gray-600 hover:bg-gray-50': activeTab !== '{{ $tabKey }}'
                            }"
                            class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all duration-200 font-medium"
                        >
                            @if(isset($tab['icon']))
                                <span class="text-lg">
                                    <i class="fas fa-{{ $tab['icon'] }}"></i>
                                </span>
                            @endif
                            {{ $tab['label'] }}
                        </button>
                    @endforeach
                </nav>
            </div>

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
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formData', () => ({
            activeTab: 'basic',
            tabs: @json(config('appv2.constants.tabs')),
            
            // フォームデータの初期化
            basic: {
                title: '',
                description: '',
                app_type: '',
                genre: '',
                status: '',
                development_start_date: '',
                development_end_date: '',
                development_period_years: 0,
                development_period_months: 0
            },
            screenshots: [],
            story: {
                motivation: '',
                challenges: '',
                future: ''
            },
            // ... 他のタブのデータ構造
        }));
    });
    </script>
</x-app-layout> 