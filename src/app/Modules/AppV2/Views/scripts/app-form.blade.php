<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('appForm', () => ({
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
            const url = `/apps-v2/${this.appId}/autosave`;
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
                    this.$dispatch('show-notification', {
                        message: '自動保存しました',
                        type: 'success'
                    });
                }
            })
            .catch(error => {
                this.$dispatch('show-notification', {
                    message: '保存に失敗しました',
                    type: 'error'
                });
                console.error('自動保存エラー:', error);
            });
        }, 2000),

        // 初期化
        init() {
            // 初期データのロード
            if (window.initialData) {
                this.formData = window.initialData;
            }
            // アプリIDの取得
            this.appId = document.querySelector('input[name="app_id"]')?.value;
        }
    }));
});
</script> 