@php
    $formData = session('form_data', []);
@endphp

<form id="app-form" method="POST" action="{{ route('apps.store') }}" enctype="multipart/form-data">
    @csrf

    <!-- プログレスバー -->
    <div class="mb-8">
        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 relative overflow-hidden">
            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500"
                 x-bind:style="{ width: progress + '%' }"></div>
        </div>
    </div>

    <!-- フォームセクション -->
    @include('app::forms.01_BasicInfoForm')
    @include('app::forms.02_DevelopmentStoryForm')
    @include('app::forms.03_HardwareSection')
    @include('app::forms.04_1_BasicDevEnvironment')
    @include('app::forms.04_2_DevToolsEnvironment')
    @include('app::forms.04_3_ArchitectureSection')
    @include('app::forms.04_4_SecuritySection')
    @include('app::forms.05_BackendSection')
    @include('app::forms.06_FrontendSection')
    @include('app::forms.07_DatabaseSection')

    <!-- 送信ボタン -->
    <div class="mt-8 flex justify-end space-x-4">
        <button type="button" onclick="history.back()" class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600">
            キャンセル
        </button>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
            {{ isset($app) ? '更新する' : '投稿する' }}
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('app-form');
    if (!form) return;

    // フォームの入力値を監視して自動保存
    form.addEventListener('input', function(e) {
        const field = e.target;
        if (!field.name || field.type === 'file') return; // ファイルフィールドをスキップ
        
        try {
            if (field.type === 'checkbox' || field.type === 'radio') {
                localStorage.setItem(`form_${field.name}`, field.checked);
            } else {
                localStorage.setItem(`form_${field.name}`, field.value);
            }
        } catch (e) {
            console.error('フォームデータの保存に失敗しました:', e);
        }
    });

    // プログレスバーの更新処理を修正
    function updateProgress() {
        const totalFields = form.querySelectorAll('input:not([type="file"]), textarea, select').length;
        let filledFields = 0;
        
        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(field => {
            if (field.value.trim() !== '') filledFields++;
        });

        const progress = Math.round((filledFields / totalFields) * 100);
        
        // プログレスバーの更新処理を修正
        if (window.progressBar) {
            window.progressBar.progress = progress;
        }
    }

    // 入力時にプログレスバーを更新
    form.addEventListener('input', updateProgress);

    // 既存のフォームデータを復元
    const formFields = form.querySelectorAll('input:not([type="file"]), textarea, select');
    try {
        formFields.forEach(field => {
            if (!field.name) return;
            const savedValue = localStorage.getItem(`form_${field.name}`);
            if (savedValue && !field.value) {
                if (field.type === 'checkbox' || field.type === 'radio') {
                    field.checked = savedValue === 'true';
                } else {
                    field.value = savedValue;
                }
            }
        });
        // 初期表示時にもプログレスバーを更新
        updateProgress();
    } catch (e) {
        console.error('フォームデータの復元に失敗しました:', e);
    }

    // フォーム送信時にローカルストレージをクリア
    form.addEventListener('submit', function() {
        try {
            formFields.forEach(field => {
                if (field.name) {
                    localStorage.removeItem(`form_${field.name}`);
                }
            });
        } catch (e) {
            console.error('フーカルストレージのクリアに失敗しました:', e);
        }
    });
});
</script>