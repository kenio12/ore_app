<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('appForm', () => ({
        appId: null,  // appIdをプロパティとして追加
        
        // タブ管理
        activeTab: 'basic',
        tabs: @json(config('appv2.constants.tabs')),

        // フォームデータ
        formData: {
            basic: {
                title: '',
                description: '',
                types: [],
                genres: [],
                app_status: '',
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

        // 自動保存（デバウンス付き）
        autoSave: Alpine.debounce(function() {
            let url = this.appId === 'create' 
                ? '/apps-v2/create/autosave'  // 新規作成用
                : `/apps-v2/${this.appId}/autosave`;  // 更新用
            
            console.log('Saving to:', url, this.formData);

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(this.formData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    this.$dispatch('autosave-success', '自動保存しました');
                }
            })
            .catch(error => {
                console.error('自動保存エラー:', error);
                this.$dispatch('autosave-error', error.message);
            });
        }, 2000),

        // 初期化
        init() {
            // appIdの取得を最優先
            const appIdInput = document.querySelector('input[name="app_id"]');
            if (appIdInput) {
                this.appId = appIdInput.value;
                console.log('Found appId:', this.appId);
            } else {
                console.error('app_id input not found');
                // 新規作成モードとして扱う
                this.appId = 'create';
            }

            // 配列の初期化を確実に
            this.formData.basic.types = this.formData.basic.types || [];
            this.formData.basic.genres = this.formData.basic.genres || [];

            // 初期データのロード
            if (window.initialData) {
                this.formData = {
                    ...this.formData,
                    ...window.initialData
                };
            }
        }
    }));
});
</script> 