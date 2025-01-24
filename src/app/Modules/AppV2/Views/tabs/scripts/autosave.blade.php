<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('formData', () => ({
        title: '',
        description: '',
        story: {
            motivation: '',
            challenges: '',
            future: ''
        },
        hardware: {
            cpu: '',
            memory: '',
            storage: ''
        },
        dev_env: {
            editor: '',
            version_control: '',
            ci_cd: ''
        },
        architecture: {
            system: '',
            patterns: ''
        },
        frontend: {
            framework: '',
            ui_library: ''
        },
        backend: {
            language: '',
            framework: ''
        },
        database: {
            type: '',
            design: ''
        },
        security: {
            auth: '',
            measures: ''
        },

        init() {
            // 保存データの読み込み
            const savedData = localStorage.getItem('appFormData');
            if (savedData) {
                const parsed = JSON.parse(savedData);
                Object.assign(this, parsed);
            }
        },

        autoSave() {
            // 自動保存処理
            localStorage.setItem('appFormData', JSON.stringify(this));
            
            // 保存成功通知
            window.dispatchEvent(new CustomEvent('autosave-success', {
                detail: '変更を自動保存しました！'
            }));
        }
    }));
});
</script> 