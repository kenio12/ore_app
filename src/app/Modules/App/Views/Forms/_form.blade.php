@php
    $formData = session('form_data', []);
@endphp

<div class="app-sections">
    <!-- プログレスバー -->
    <div class="mb-8">
        <div class="steps flex justify-between mb-8">
            <div class="step {{ $currentSection === 'basic-info' ? 'active' : '' }} 
                         {{ $app->basic_info ? 'completed' : '' }}">
                <span class="step-number">1</span>
                <span class="step-text">基本情報</span>
                @if($app->basic_info)
                    <span class="step-check">✓</span>
                @endif
            </div>
            <!-- 他のステップも同様 -->
        </div>
    </div>

    <!-- 現在のセクションのみを表示 -->
    @if($currentSection === 'basic-info')
        @include('app::forms.01_BasicInfoForm')
    @elseif($currentSection === 'development-story')
        @include('app::forms.02_DevelopmentStoryForm')
    @endif
    <!-- 他のセクションも同様 -->
</div>

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