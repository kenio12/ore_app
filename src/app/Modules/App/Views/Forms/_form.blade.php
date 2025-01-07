@php
    $formData = session('form_data', []);
@endphp

<form id="app-form" method="POST" action="{{ route('apps.store') }}" enctype="multipart/form-data">
    @csrf

    <!-- プログレスバー -->
    <div class="mb-8" 
         x-data="{ progress: 0 }" 
         x-init="$nextTick(() => window.progressBar = $data)">
        <div class="bg-gray-200 rounded-full h-2.5">
            <div class="bg-blue-600 h-2.5 rounded-full" 
                 x-bind:style="{ width: progress + '%' }"></div>
        </div>
    </div>

    <!-- フォームセクション -->
    @include('App::Forms.01_BasicInfoForm')
    @include('App::Forms.02_DevelopmentStoryForm')
    @include('App::Forms.03_HardwareSection')
    @include('App::Forms.04_1_BasicDevEnvironment')
    @include('App::Forms.04_2_DevToolsEnvironment')
    @include('App::Forms.04_3_ArchitectureSection')
    @include('App::Forms.05_BackendSection')
    @include('App::Forms.06_FrontendSection')
    @include('App::Forms.07_DatabaseSection')

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

<!-- フォームデータ保持用スクリプト -->
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