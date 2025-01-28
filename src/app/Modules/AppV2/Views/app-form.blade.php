<x-app-layout>
    {{-- 超最先端のグラデーションヘッダー --}}
    <div x-data="appForm" 
         x-init="
            $watch('saveMessage', value => console.log('saveMessage changed:', value));
            
            // グローバルなイベントリスナーを追加
            window.onbeforeunload = function(e) {
                const defaultTitle = '{{ config('appv2.constants.default_app_title') }}';
                if (formData.basic.title === defaultTitle) {
                    e.preventDefault();
                    e.returnValue = '';
                    
                    // 非同期処理を同期的に実行
                    const confirmed = confirm('アプリ名を変えないと削除されますよ？削除していいですか？');
                    if (confirmed) {
                        const xhr = new XMLHttpRequest();
                        xhr.open('DELETE', `/apps-v2/${document.getElementById('app_id_input').value}`, false); // 同期リクエスト
                        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name=csrf-token]').content);
                        xhr.send();
                        
                        if (xhr.status === 200) {
                            window.location.href = '/apps-v2';
                        }
                    }
                    return false;
                }
            };
         "
    >
        <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-pink-50 py-12">
            {{-- app_idをここに追加（常に$app->idが存在する前提） --}}
            <input type="hidden" name="app_id" value="{{ $app->id }}" id="app_id_input">

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
            Alpine.data('appForm', () => ({
                appId: null,
                activeTab: 'basic',
                
                // formDataの初期化を修正
                formData: {
                    basic: {
                        title: '',
                        description: '',
                        types: [],
                        genres: [],
                        app_status: '',
                        status: '',
                        demo_url: '',
                        github_url: '',
                        development_start_date: '',
                        development_end_date: '',
                        development_period_years: 0,
                        development_period_months: 0,
                        motivation: '',
                        purpose: ''
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

                // タブ切り替え
                switchTab(tabId) {
                    this.activeTab = tabId;
                    console.log('Tab switched to:', tabId);
                },

                // 自動保存の改善
                async autoSave() {
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
                                screenshots: this.formData.screenshots // スクリーンショットデータを明示的に含める
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
                    if (!this.shouldShowMessage) return;
                    
                    this.saveMessage = message;
                    console.log('Showing message:', message);
                    
                    setTimeout(() => {
                        this.saveMessage = null;
                    }, 3000);
                },

                // 初期化
                init() {
                    // appIdを固定値として保持
                    const appIdInput = document.querySelector('input[name="app_id"]');
                    this.appId = appIdInput ? appIdInput.value : null;
                    
                    // デバッグログを追加
                    console.log('Fixed appId initialized as:', this.appId);

                    // 既存データの読み込みを改善
                    const savedData = {!! isset($app) ? json_encode($app) : 'null' !!};
                    if (savedData) {
                        console.log('Loading saved data:', savedData);

                        // スクリーンショットデータの保持を改善
                        if (savedData.screenshots) {
                            this.formData.screenshots = savedData.screenshots.map(screenshot => ({
                                id: screenshot.id,
                                public_id: screenshot.cloudinary_public_id,
                                url: screenshot.url,
                                order: screenshot.order
                            }));
                        }

                        // 基本データの復元（既存のまま）
                        this.formData.basic = {
                            title: savedData.title || '',
                            description: savedData.description || '',
                            types: Array.isArray(savedData.app_types) 
                                ? savedData.app_types 
                                : (savedData.app_types ? JSON.parse(savedData.app_types) : []),
                            genres: Array.isArray(savedData.genres) 
                                ? savedData.genres 
                                : (savedData.genres ? JSON.parse(savedData.genres) : []),
                            app_status: savedData.app_status || '',
                            status: savedData.status || 'draft',
                            demo_url: savedData.demo_url || '',
                            github_url: savedData.github_url || '',
                            development_start_date: savedData.development_start_date 
                                ? new Date(savedData.development_start_date).toISOString().split('T')[0]
                                : '',
                            development_end_date: savedData.development_end_date 
                                ? new Date(savedData.development_end_date).toISOString().split('T')[0]
                                : '',
                            development_period_years: savedData.development_period_years || 0,
                            development_period_months: savedData.development_period_months || 0,
                            motivation: savedData.motivation || '',
                            purpose: savedData.purpose || '',
                        };

                        // ストーリーデータの復元
                        this.formData.story = {
                            development_trigger: savedData.development_trigger || '',
                            development_hardship: savedData.development_hardship || '',
                            development_tearful: savedData.development_tearful || '',
                            development_enjoyable: savedData.development_enjoyable || '',
                            development_funny: savedData.development_funny || '',
                            development_impression: savedData.development_impression || '',
                            development_oneword: savedData.development_oneword || ''
                        };

                        // その他のセクションの復元
                        ['hardware', 'dev_env', 'architecture', 'frontend', 'backend', 'database', 'security']
                        .forEach(section => {
                            if (savedData[section]) {
                                this.formData[section] = {
                                    ...this.formData[section],
                                    ...savedData[section]
                                };
                            }
                        });
                    }

                    // スクリーンショット更新イベントのハンドリングを改善
                    this.$el.addEventListener('screenshots-updated', (event) => {
                        console.log('Screenshots update event received:', event.detail);
                        // 完全に置き換える（重複を避ける）
                        this.formData.screenshots = event.detail;
                        
                        // 自動保存をトリガー
                        this.autoSave();
                    });

                    // 各種ウォッチャーを設定
                    this.$watch('formData.basic', (value) => {
                        console.log('Basic data changed:', value);
                        this.dirtySections.add('basic');
                    }, { deep: true });

                    this.$watch('formData.basic.types', (value) => {
                        console.log('Types changed:', value);
                        this.dirtySections.add('basic');
                    });

                    this.$watch('formData.basic.genres', (value) => {
                        console.log('Genres changed:', value);
                        this.dirtySections.add('basic');
                    });

                    // 初期状態を保存
                    this.saveInitialState();
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
                }
            }));
        });

        // デバッグ用
        window.addEventListener('load', () => {
            console.log('Alpine.js version:', Alpine.version);
            console.log('Alpine.js data:', Alpine.$data(document.querySelector('[x-data]')));
        });
    </script>
</x-app-layout> 