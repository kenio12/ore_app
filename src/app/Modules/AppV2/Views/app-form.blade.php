<x-app-layout>
    {{-- 超最先端のグラデーションヘッダー --}}
    <div x-data="appForm" 
         @submit.prevent  {{-- フォームのsubmitを完全に防ぐ --}}
         x-init="
            if (!window.appFormInitialized) {
                window.appFormInitialized = true;
                init();
                
                // 新規作成時の初期化
                if (!formData.basic.title) {
                    formData.basic = {
                        title: '{{ config('appv2.constants.app_defaults.title') }}',
                        description: '',
                        motivation: '',
                        purpose: '',
                        types: [],
                        genres: [],
                        app_status: 'draft',
                        status: 'draft',
                        demo_url: '',
                        github_url: '',
                        development_start_date: '',
                        development_end_date: '',
                        development_period_years: 0,
                        development_period_months: 0
                    };
                }
                
                // 一度だけ実行されるべき処理
                $watch('saveMessage', value => {
                    if (value) console.log('saveMessage changed:', value);
                });
                
                // 最初に一度だけIDを取得
                @php
                    $currentAppId = $app->id ?? null;
                @endphp
            }
         "
    >
        <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-pink-50 py-12">
            {{-- hiddenフィールド --}}
            <input type="hidden" name="app_id" value="{{ $currentAppId }}" id="app_id_input">

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
                                    <option value="draft" class="font-bold text-gray-600 select-none">✏️ 非公開</option>
                                    <option value="public" class="font-bold text-green-600 select-none">🌐 公開</option>
                                </select>
                            </div>
                        </div>
                        <p class="mt-3 text-xl text-gray-600">
                            下のタブの内容を入力し終え、上の非公開ボタンを押せば、あなたのアプリを公開できます。
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

                {{-- 上部タブナビゲーション --}}
                @include('AppV2::components.tab-navigation', ['sections' => $sections])

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

                {{-- 下部タブナビゲーション（上部と同じものを再利用） --}}
                <div class="mt-8">
                    @include('AppV2::components.tab-navigation', ['sections' => $sections])
                </div>
            </div>
        </div>
        
        {{-- モダンなモーダル --}}
        @include('AppV2::components.screenshot-modal')

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

    {{-- ここから下にすべてのJavaScriptをまとめる --}}
    <script>
        document.addEventListener('alpine:init', () => {
            if (!window.alpineInitialized) {
                window.alpineInitialized = true;
                
                Alpine.data('appForm', () => ({
                    appId: null,
                    isInitialized: false,
                    isInitializing: false,
                    activeTab: 'basic',
                    
                    // formDataの初期化を修正
                    formData: {
                        basic: {
                            title: '',
                            description: '',
                            motivation: '',
                            purpose: '',
                            types: [],
                            genres: [],
                            app_status: 'draft',
                            status: 'draft',
                            demo_url: '',
                            github_url: '',
                            development_start_date: '',
                            development_end_date: '',
                            development_period_years: 0,
                            development_period_months: 0
                        },
                        screenshots: [], // ここが重要！
                        story: {
                            development_trigger: '',
                            development_hardship: '',
                            development_tearful: '',
                            development_enjoyable: '',
                            development_funny: '',
                            development_impression: '',
                            development_oneword: ''
                        },
                        hardware: {
                            device_types: [],
                            os_types: [],
                            cpu_types: [],
                            memory_sizes: [],
                            storage_types: []
                        },
                        dev_env: {
                            team_sizes: '',
                            virtualization_tools: [],
                            editors: [],
                            version_control: [],
                            monitor_counts: '',
                            monitor_sizes: [],
                            monitor_resolutions: [],
                            communication: []
                        },
                        architecture: {
                            patterns: [],
                            design_patterns: [],
                            hints: []
                        },
                        frontend: {
                            languages: [],
                            frameworks: [],
                            css_frameworks: []
                        },
                        backend: {
                            languages: [],
                            frameworks: [],
                            package_hints: []
                        },
                        database: {
                            types: [],
                            orms: [],
                            caches: [],
                            hosting_services: []
                        },
                        security: {
                            security_measures: [],
                            testing_tools: [],
                            code_quality_tools: []
                        }
                    },

                    autoSaveTimer: null,
                    inputTimer: null,
                    lastSavedSections: {},
                    dirtySections: new Set(),
                    saveMessage: null,
                    shouldShowMessage: true,
                    lastAutoSave: null,

                    // タイトル入力用の新しい変数
                    titleInputTimer: null,
                    isNewApp: {{ $currentAppId ? 'false' : 'true' }},  // 新規作成かどうか

                    // タブ切り替え
                    switchTab(tabId) {
                        this.activeTab = tabId;
                        console.log('Tab switched to:', tabId);
                    },

                    // 自動保存の改善
                    async autoSave() {
                        // 初期化中は自動保存しない
                        if (this.isInitializing) {
                            console.log('Skipping autosave during initialization');
                            return;
                        }
                        
                        // 前回の自動保存から一定時間経過していない場合はスキップ
                        if (this.lastAutoSave && Date.now() - this.lastAutoSave < 5000) {
                            console.log('Skipping autosave - too soon');
                            return;
                        }
                        
                        this.lastAutoSave = Date.now();
                        try {
                            const response = await fetch(`/apps-v2/${this.appId}/autosave`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    formData: this.formData,
                                    screenshots: this.formData.screenshots
                                })
                            });

                            const result = await response.json();
                            if (result.success) {
                                this.showSaveMessage('保存しました');
                            }
                        } catch (error) {
                            console.error('Autosave error:', error);
                            this.showSaveMessage('保存に失敗しました');
                        }
                    },

                    // 保存メッセージ表示
                    showSaveMessage(message) {
                        // メッセージが同じ場合は表示しない
                        if (this.saveMessage === message) {
                            console.log('Skipping duplicate message:', message);
                            return;
                        }
                        
                        // 初期化中は表示しない
                        if (this.isInitializing) {
                            console.log('Skipping message during initialization:', message);
                            return;
                        }
                        
                        this.saveMessage = message;
                        console.log('Showing message:', message);
                        
                        setTimeout(() => {
                            if (this.saveMessage === message) {
                                this.saveMessage = null;
                            }
                        }, 3000);
                    },

                    // 初期化
                    init() {
                        console.log('Form initialized with:', this.formData);
                        this.$watch('formData.basic.title', (value) => {
                            console.log('Title changed to:', value);
                            if (value && value.trim()) {
                                this.handleTitleInput();
                            }
                        });
                    },

                    // 初期状態保存
                    saveInitialState() {
                        const sections = Object.keys(this.formData);
                        sections.forEach(section => {
                            this.lastSavedSections[section] = JSON.stringify(this.formData[section]);
                        });
                        console.log('Initial state saved');
                    },

                    // フォーム初期化
                    initializeForm(initialData) {
                        this.formData = initialData;
                        console.log('Form initialized with:', initialData);
                    },

                    // クリーンアップ
                    destroy() {
                        if (this.autoSaveTimer) {
                            clearInterval(this.autoSaveTimer);
                        }
                        if (this.inputTimer) {
                            clearTimeout(this.inputTimer);
                        }
                        console.log('Resources cleaned up');
                    },

                    // デフォルトタイトルチェック関数
                    checkDefaultTitle() {
                        const defaultTitle = '{{ config('appv2.constants.default_app_title') }}';  // configから取得
                        if (this.formData.basic.title === defaultTitle || !this.formData.basic.title) {
                            // デフォルトタイトルのままか空の場合、警告を表示
                            this.showSaveMessage('まずはアプリ名を入力してください！');
                            
                            // 要素の存在確認を追加
                            const titleInput = this.$refs.titleInput;  // 参照を使用
                            if (titleInput) {
                                titleInput.classList.add('border-red-500');
                            }
                        } else {
                            // 変更されている場合は強調表示を解除
                            const titleInput = this.$refs.titleInput;  // 参照を使用
                            if (titleInput) {
                                titleInput.classList.remove('border-red-500');
                            }
                        }
                    },

                    // タイトル入力時の処理を一本化
                    async handleTitleInput() {
                        // すでにIDがある場合は更新処理のみ
                        if (this.appId) {
                            await this.handleInput();
                            return;
                        }

                        // タイトルが空の場合は何もしない
                        if (!this.formData.basic.title.trim()) {
                            return;
                        }

                        // 新規作成処理（初回のみ）
                        try {
                            const response = await fetch('/apps-v2', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    title: this.formData.basic.title,
                                    type: 'initial'
                                })
                            });

                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }

                            const data = await response.json();
                            if (data.success) {
                                this.appId = data.appId;
                                window.history.pushState({}, '', `/apps-v2/${data.appId}/edit`);
                                this.showSaveMessage('アプリを作成しました！');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            this.showSaveMessage('エラーが発生しました');
                        }
                    },

                    // 自動保存処理を修正
                    async triggerAutoSave() {
                        if (!this.appId) {
                            console.log('先にタイトルを入力してください');
                            return;
                        }

                        try {
                            const response = await fetch(`/apps-v2/${this.appId}/autosave`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    formData: this.formData
                                })
                            });

                            const result = await response.json();
                            if (result.success) {
                                this.showSaveMessage('保存しました');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            this.showSaveMessage('保存に失敗しました');
                        }
                    },

                    // 自動保存の初期化
                    initAutoSave() {
                        if (this.autoSaveTimer) return;
                        // ... 既存の自動保存コード ...
                    },

                    // その他のフィールド入力時の処理
                    async handleInput() {
                        if (!this.appId) return;
                        
                        try {
                            const response = await fetch(`/apps-v2/${this.appId}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify(this.formData)
                            });

                            if (response.ok) {
                                const data = await response.json();
                                // IDを取得したら、URLを編集画面に切り替え
                                if (data.id) {
                                    window.history.pushState({}, '', `/apps-v2/${data.id}/edit`);
                                    this.appId = data.id;  // appIdを保存
                                    this.showSaveMessage('保存しました');
                                }
                            }
                        } catch (error) {
                            console.error('Error:', error);
                        }
                    }
                }));
            }
        });
    </script>
</x-app-layout> 