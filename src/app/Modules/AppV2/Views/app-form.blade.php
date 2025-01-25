<x-app-layout>
    {{-- 超最先端のグラデーションヘッダー --}}
    <div x-data="appForm">
        <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-pink-50 py-12">
            {{-- app_idをここに追加 --}}
            <input type="hidden" name="app_id" value="{{ isset($app->id) ? $app->id : 'create' }}">

            {{-- 自動保存通知 --}}
            @include('AppV2::components.autosave-notification')

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{-- ヘッダー部分を改善：タイトルと公開設定を統合 --}}
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl p-8 mb-12 shadow-xl border border-white/20">
                    <div>
                        <div class="flex items-center gap-4">
                            <h1 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600">
                                アプリ登録
                            </h1>
                            {{-- 公開設定をよりシンプルに + 自動保存追加 --}}
                            <div class="relative">
                                <select 
                                    x-model="formData.basic.status"
                                    @change="autoSave"
                                    class="rounded-full border-2 border-pink-200 px-6 py-2
                                           focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20
                                           hover:border-pink-300 transition-all duration-300
                                           font-bold text-lg bg-white select-none"
                                    x-init="formData.basic.status = formData.basic.status || 'draft'"
                                >
                                    <option value="draft" class="font-bold text-gray-600 select-none">✏️ 下書き</option>
                                    <option value="public" class="font-bold text-green-600 select-none">🌐 公開</option>
                                </select>
                            </div>
                        </div>
                        <p class="mt-3 text-xl text-gray-600">
                            下のタブの項目を入力し終え、上の下書きボタンを押せば、あなたのアプリを公開できます。
                        </p>
                    </div>
                    {{-- 公開時の注意事項 --}}
                    <div x-show="formData.basic.status === 'public'" 
                         x-transition
                         class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="flex items-center gap-2 text-yellow-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span class="font-bold">公開前の確認事項：</span>
                        </div>
                        <ul class="mt-2 ml-6 list-disc text-sm text-yellow-600">
                            <li>基本情報が正しく入力されていますか？</li>
                            <li>機密情報や個人情報が含まれていませんか？</li>
                            <li>著作権や利用規約に違反していませんか？</li>
                        </ul>
                    </div>
                </div>

                {{-- タブナビゲーション --}}
                @include('AppV2::components.tab-navigation')

                {{-- タブコンテンツ --}}
                <div class="bg-white/50 backdrop-blur-lg rounded-xl p-6 shadow-xl">
                    <template x-if="formData">
                        <div>
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
                    </template>
                </div>
            </div>
        </div>
        
        {{-- モダンなモーダル --}}
        @include('AppV2::components.screenshot-modal')

        {{-- Alpine.js初期化スクリプト --}}
        @include('AppV2::tabs.scripts.app-form-scripts')

        {{-- 保存メッセージ（position: fixedを使用） --}}
        <template x-if="saveMessage">
            <div 
                class="fixed bottom-4 right-4 px-4 py-2 rounded-lg bg-green-500 text-white shadow-lg z-50"
                x-text="saveMessage"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-2"
            ></div>
        </template>
    </div>

    {{-- スタイルを追加 --}}
    <style>
        .fixed {
            position: fixed !important;
        }
        select {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: none !important;
            background-color: white !important;
            padding-right: 1rem !important;
            cursor: pointer !important;
        }
        select::-ms-expand {
            display: none !important;
        }
        option {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: none !important;
            padding: 0.5rem !important;
        }
        /* Firefoxの特殊なスタイルを上書き */
        @-moz-document url-prefix() {
            select {
                text-indent: 0 !important;
                text-overflow: '' !important;
                padding-right: 1rem !important;
            }
            
            option {
                -moz-appearance: none !important;
                appearance: none !important;
            }
        }
        /* Chromeの特殊なスタイルを上書き */
        @media screen and (-webkit-min-device-pixel-ratio:0) {
            select {
                -webkit-appearance: none !important;
                appearance: none !important;
            }
        }
    </style>

    {{-- Alpine.jsのデバッグモードを有効化 --}}
    <script>
        window.addEventListener('load', () => {
            console.log('Alpine.js version:', Alpine.version);
            console.log('Alpine.js data:', Alpine.$data(document.querySelector('[x-data]')));
        });
    </script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div 
                    class="p-6 text-gray-900" 
                    x-data="appForm"
                    x-init="$watch('saveMessage', value => console.log('saveMessage changed:', value))"
                >
                    {{-- タブナビゲーション --}}
                    <div class="mb-4 border-b border-gray-200">
                        {{-- ... 既存のタブナビゲーション ... --}}
                    </div>

                    {{-- タブコンテンツ --}}
                    <div class="mt-4">
                        {{-- ... 既存のタブコンテンツ ... --}}
                    </div>

                    {{-- デバッグ用ボタン --}}
                    <button
                        @click="showSaveMessage('テストメッセージ')"
                        class="fixed top-4 right-4 px-4 py-2 bg-blue-500 text-white rounded-lg"
                    >
                        テストメッセージ表示
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 