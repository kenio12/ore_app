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
        inputTimer: null,  // 追加：入力用のタイマー
        lastSavedSections: {},
        dirtySections: new Set(), // 変更のあったセクション
        saveMessage: '',  // 初期値を空文字列に
        shouldShowMessage: true,  // メッセージ表示制御用フラグ

        // タブ切り替え
        switchTab(tabId) {
            this.activeTab = tabId;
        },

        // 自動保存（データベースとローカルストレージの両方に保存）
        async autoSave() {
            console.log('Starting autoSave with data:', this.formData);
            try {
                const isCreate = !this.appId || this.appId === 'create';
                const saveUrl = isCreate 
                    ? '/apps-v2/create/autosave'
                    : `/apps-v2/${this.appId}/autosave`;

                const response = await fetch(saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        formData: {
                            ...this.formData,
                            basic: {
                                ...this.formData.basic,
                                updated_at: new Date().toISOString()
                            }
                        }
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                console.log('Save result:', result);
                
                if (result.success) {
                    if (this.shouldShowMessage) {
                        window.dispatchEvent(new CustomEvent('autosave-success', {
                            detail: '保存しました'
                        }));
                    }
                } else {
                    throw new Error(result.message || '保存に失敗しました');
                }
            } catch (error) {
                console.error('Autosave error:', error);
                window.dispatchEvent(new CustomEvent('autosave-error', {
                    detail: '保存に失敗しました'
                }));
            }
        },

        // 初期化
        init() {
            // appIdの取得
            const appIdInput = document.querySelector('input[name="app_id"]');
            this.appId = appIdInput ? appIdInput.value : null;
            
            console.log('Initializing with appId:', this.appId);

            // 既存データの取得と適用
            const savedData = {!! isset($app) ? json_encode($app) : 'null' !!};
            console.log('Saved data:', savedData);

            if (savedData) {
                // 基本データの復元（nullチェックを追加）
                this.formData.basic = {
                    title: savedData.title || '',
                    description: savedData.description || '',
                    types: Array.isArray(savedData.app_types) ? savedData.app_types : [],
                    genres: Array.isArray(savedData.genres) ? savedData.genres : [],
                    app_status: savedData.app_status || '',
                    status: savedData.status || 'draft',
                    demo_url: savedData.demo_url || '',
                    github_url: savedData.github_url || '',
                    development_start_date: savedData.development_start_date || '',
                    development_end_date: savedData.development_end_date || '',
                    development_period_years: savedData.development_period_years || 0,
                    development_period_months: savedData.development_period_months || 0
                };

                // その他のデータの復元
                if (savedData.data) {
                    try {
                        const parsedData = typeof savedData.data === 'string' 
                            ? JSON.parse(savedData.data) 
                            : savedData.data;

                        // 各セクションのデータを復元
                        const sections = ['screenshots', 'story', 'hardware', 'dev_env', 
                                       'architecture', 'frontend', 'backend', 'database', 'security'];
                        
                        sections.forEach(section => {
                            if (parsedData[section]) {
                                // 配列の場合は新しい配列として初期化
                                if (Array.isArray(this.formData[section])) {
                                    this.formData[section] = [...(parsedData[section] || [])];
                                } else {
                                    // オブジェクトの場合は各プロパティをマージ
                                    Object.assign(this.formData[section], parsedData[section]);
                                }
                            }
                        });
                    } catch (e) {
                        console.error('Error parsing saved data:', e);
                    }
                }

                // スクリーンショットの特別処理
                if (savedData.screenshots) {
                    try {
                        this.formData.screenshots = Array.isArray(savedData.screenshots) 
                            ? savedData.screenshots 
                            : JSON.parse(savedData.screenshots) || [];
                    } catch (e) {
                        console.error('Error parsing screenshots:', e);
                        this.formData.screenshots = [];
                    }
                }
            }

            console.log('Initialized form data:', this.formData);

            // 自動保存の設定を修正
            this.$watch('formData', (value, oldValue) => {
                // 入力欄にフォーカスがある場合は常にメッセージを抑制
                if (document.activeElement && 
                    (document.activeElement.tagName.toLowerCase() === 'input' || 
                     document.activeElement.tagName.toLowerCase() === 'textarea')) {
                    this.shouldShowMessage = false;
                    return;
                }

                if (JSON.stringify(value) !== JSON.stringify(oldValue)) {
                    if (elementType === 'checkbox' || elementTag === 'select') {
                        this.shouldShowMessage = true;
                        this.autoSave();
                    } else {
                        // 入力中は自動保存のみ
                        if (this.inputTimer) {
                            clearTimeout(this.inputTimer);
                        }
                        
                        this.inputTimer = setTimeout(() => {
                            this.autoSave();
                        }, 2000);
                    }
                }
            }, { deep: true });

            // フォーカスイベントの処理を修正
            document.querySelectorAll('input, textarea').forEach(element => {
                // フォーカス時はメッセージを抑制
                element.addEventListener('focus', () => {
                    this.shouldShowMessage = false;
                });

                // フォーカスが外れて500ms後にメッセージを有効化
                element.addEventListener('blur', () => {
                    setTimeout(() => {
                        this.shouldShowMessage = true;
                        if (this.inputTimer) {
                            clearTimeout(this.inputTimer);
                            this.autoSave();
                        }
                    }, 500);
                });
            });

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

            // グローバルformDataの初期化
            window.formData = this.formData;

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
            if (this.inputTimer) {
                clearTimeout(this.inputTimer);
            }
        }
    }));
});
</script> 