<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('appForm', () => ({
        appId: null,
        activeTab: 'basic',
        
        // formDataの初期化を確実に
        formData: {
            basic: {
                title: '',
                description: '',
                types: [],      // 配列として初期化
                genres: [],     // 配列として初期化
                app_status: '',
                status: '',
                demo_url: '',
                github_url: '',
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
        lastSavedSections: {},
        dirtySections: new Set(), // 変更のあったセクション
        saveMessage: '',  // 初期値を空文字列に

        // タブ切り替え
        switchTab(tabId) {
            this.activeTab = tabId;
        },

        // 自動保存（データベースとローカルストレージの両方に保存）
        async autoSave() {
            try {
                // 現在のページが新規作成か編集かを判断
                const isCreate = !this.appId || this.appId === 'create';
                
                // URLを適切に設定
                const saveUrl = isCreate 
                    ? '/apps-v2/create/autosave'
                    : `/apps-v2/${this.appId}/autosave`;

                // 保存するデータを準備
                const dirtyData = {};
                this.dirtySections.forEach(section => {
                    dirtyData[section] = this.formData[section];
                });

                const response = await fetch(saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        formData: dirtyData
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                
                if (result.success) {
                    // 新規作成時は返却されたIDを保存
                    if (isCreate && result.app_id) {
                        this.appId = result.app_id;
                        // URLを更新（ページ遷移なし）
                        window.history.pushState({}, '', `/apps-v2/${result.app_id}/edit`);
                    }
                    
                    this.dirtySections.clear();
                    this.showSaveMessage('保存しました');
                } else {
                    throw new Error(result.message || '保存に失敗しました');
                }
            } catch (error) {
                console.error('Autosave error:', error);
                this.showSaveMessage('保存に失敗しました', true);
            }
        },

        // 保存メッセージを表示する関数
        showSaveMessage(message, isError = false) {
            this.saveMessage = message;
            
            setTimeout(() => {
                this.saveMessage = '';
            }, 3000);
        },

        // 初期化
        init() {
            console.log('AppForm initialized');
            
            // appIdの取得
            const appIdInput = document.querySelector('input[name="app_id"]');
            const isNewCreate = !appIdInput || appIdInput.value === 'create';
            
            if (isNewCreate) {
                // 新規作成時は初期値を設定
                this.formData = {
                    basic: {
                        title: '',
                        description: '',
                        types: [],
                        genres: [],
                        app_status: '',
                        status: 'draft',  // デフォルトは下書き
                        demo_url: '',
                        github_url: '',
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
                };
            } else {
                // 既存データのロード
                const savedData = {!! isset($app) ? json_encode($app) : 'null' !!};
                if (savedData) {
                    this.formData = {
                        ...this.formData,
                        basic: {
                            ...this.formData.basic,
                            ...savedData.basic
                        },
                        screenshots: savedData.screenshots || []
                    };
                }

                // セッションデータの復元（編集時のみ）
                const sessionData = {!! session('app_form_data') ? json_encode(session('app_form_data')) : 'null' !!};
                if (sessionData) {
                    this.formData = {
                        ...this.formData,
                        basic: {
                            ...this.formData.basic,
                            ...sessionData.basic
                        },
                        screenshots: sessionData.screenshots || []
                    };
                }
            }

            this.saveInitialState();

            // セクション監視の設定
            const sections = ['basic', 'screenshots', 'story', 'hardware', 'dev_env', 
                            'architecture', 'frontend', 'backend', 'database', 'security'];
            
            sections.forEach(section => {
                this.$watch(`formData.${section}`, () => {
                    this.dirtySections.add(section);
                    console.log(`Section ${section} changed`);
                }, { deep: true });
            });

            // 自動保存タイマーの設定（60秒）
            this.autoSaveTimer = setInterval(() => {
                if (this.dirtySections.size > 0) {
                    this.autoSave();
                }
            }, 60000);

            // ページを離れる時の処理
            window.addEventListener('beforeunload', (event) => {
                if (this.dirtySections.size > 0) {
                    event.preventDefault();
                    event.returnValue = '';
                }
            });

            console.log('Initial formData:', this.formData);
            // デバッグ用
            console.log('Initial formData structure:', this.formData);

            // appIdの取得
            if (appIdInput) {
                this.appId = appIdInput.value;
            }

            // グローバルformDataの初期化
            window.formData = this.formData;

            // フォームデータの変更を監視
            this.$watch('formData', (value) => {
                window.formData = value;
                window.dispatchEvent(new CustomEvent('formDataUpdated'));
            }, { deep: true });

            // デバッグ用
            console.log('After initialization:', this.formData);

            // デバッグ用のウォッチャーを追加
            this.$watch('formData.basic.types', (value) => {
                console.log('Types changed:', value);
            });
            this.$watch('formData.basic.genres', (value) => {
                console.log('Genres changed:', value);
            });

            // スクリーンショットの更新をリッスン
            this.$watch('formData.screenshots', (value) => {
                console.log('Screenshots updated:', value);
                this.autoSave();
            });

            // スクリーンショット更新イベントのハンドリング
            this.$el.addEventListener('screenshots-updated', (event) => {
                this.formData.screenshots = event.detail;
            });
        },

        // 初期状態を保存
        saveInitialState() {
            const sections = Object.keys(this.formData);
            sections.forEach(section => {
                this.lastSavedSections[section] = JSON.stringify(this.formData[section]);
            });
        },

        destroy() {
            if (this.autoSaveTimer) {
                clearInterval(this.autoSaveTimer);
            }
        }
    }));
});
</script> 