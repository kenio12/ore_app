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

        // タブ切り替え
        switchTab(tabId) {
            this.activeTab = tabId;
        },

        // 自動保存
        autoSave: Alpine.debounce(function() {
            console.log('Saving data:', this.formData);

            let url = this.appId === 'create' 
                ? '/apps-v2/create/autosave' 
                : `/apps-v2/${this.appId}/autosave`;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(this.formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // イベント名を統一
                    window.dispatchEvent(new CustomEvent('autosave-success', {
                        detail: '変更を自動保存しました！'
                    }));
                }
            })
            .catch(error => {
                console.error('Auto save error:', error);
                window.dispatchEvent(new CustomEvent('autosave-error', {
                    detail: '自動保存に失敗しました'
                }));
            });
        }, 1000),

        // 初期化
        init() {
            console.log('Initial formData:', this.formData);
            // デバッグ用
            console.log('Initial formData structure:', this.formData);

            // appIdの取得
            const appIdInput = document.querySelector('input[name="app_id"]');
            if (appIdInput) {
                this.appId = appIdInput.value;
            }

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

            // セッションデータの復元
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
        }
    }));
});
</script> 