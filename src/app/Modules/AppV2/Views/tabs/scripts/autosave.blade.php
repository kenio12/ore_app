<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('formData', () => ({
        // タブ定義（PHPから取得した定義を使用）
        activeTab: 'basic',
        tabs: @json(config('appv2.constants.tabs')),

        // フォームデータ（定数から選択肢を取得）
        basic: {
            title: '',
            description: '',
            app_type: '',  // app_typesから選択
            genre: '',     // genresから選択
            status: '',    // app_statusから選択
            development_start_date: '',
            development_end_date: '',
            development_period_years: 0,
            development_period_months: 0
        },
        screenshots: {
            images: []
        },
        story: {
            motivation: '',
            challenges: '',
            future: ''
        },
        hardware: {
            device_type: '',  // hardware.device_typesから選択
            os_type: '',      // hardware.os_typesから選択
            cpu_type: '',     // hardware.cpu_typesから選択
            memory_size: '',  // hardware.memory_sizesから選択
            storage_type: ''  // hardware.storage_typesから選択
        },
        dev_env: {
            team_size: '',           // basic_dev_env.team_sizesから選択
            virtualization: [],      // basic_dev_env.virtualization_toolsから選択（複数可）
            editor: '',             // basic_dev_env.editorsから選択
            version_control: [],     // basic_dev_env.version_controlから選択（複数可）
            monitor_count: '',       // basic_dev_env.monitor_countsから選択
            monitor_size: '',        // basic_dev_env.monitor_sizesから選択
            monitor_resolution: '',   // basic_dev_env.monitor_resolutionsから選択
            communication: []        // basic_dev_env.communicationから選択（複数可）
        },
        architecture: {
            description: '',
            patterns: [],           // architecture.patternsから選択（複数可）
            design_patterns: []     // architecture.design_patternsから選択（複数可）
        },
        frontend: {
            languages: [],          // frontend.languagesから選択（複数可）
            frameworks: [],         // frontend.frameworksから選択（複数可）
            css_frameworks: []      // frontend.css_frameworksから選択（複数可）
        },
        backend: {
            languages: [],          // backend.languagesから選択（複数可）
            frameworks: [],         // backend.frameworksから選択（複数可）
            packages: ''            // backend.package_hintsを参考に入力
        },
        database: {
            type: '',              // database.typesから選択
            orm: '',               // database.ormsから選択
            cache: '',             // database.cachesから選択
            hosting: '',           // database.hosting_servicesから選択
            design: '',
            schema: '',
            relationships: ''
        },
        security: {
            measures: [],          // security_quality.security_measuresから選択（複数可）
            testing: [],           // security_quality.testing_toolsから選択（複数可）
            code_quality: []       // security_quality.code_quality_toolsから選択（複数可）
        },

        init() {
            try {
                const savedData = localStorage.getItem('appFormData');
                if (savedData) {
                    const parsed = JSON.parse(savedData);
                    Object.assign(this, parsed);
                }
            } catch (error) {
                console.error('データの読み込みに失敗しました:', error);
                // 破損したデータを削除
                localStorage.removeItem('appFormData');
            }
        },

        autoSave() {
            try {
                const dataToSave = {
                    basic: this.basic,
                    screenshots: this.screenshots,
                    story: this.story,
                    hardware: this.hardware,
                    dev_env: this.dev_env,
                    architecture: this.architecture,
                    frontend: this.frontend,
                    backend: this.backend,
                    database: this.database,
                    security: this.security
                };

                localStorage.setItem('appFormData', JSON.stringify(dataToSave));
                
                window.dispatchEvent(new CustomEvent('autosave-success', {
                    detail: '変更を自動保存しました！'
                }));
            } catch (error) {
                console.error('自動保存に失敗しました:', error);
                window.dispatchEvent(new CustomEvent('autosave-error', {
                    detail: '保存に失敗しました。'
                }));
            }
        }
    }));
});
</script> 