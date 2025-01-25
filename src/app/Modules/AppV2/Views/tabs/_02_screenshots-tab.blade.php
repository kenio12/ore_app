@php
    // データの初期化
    $formData = $data ?? [];
    $app = $app ?? null;
    $viewOnly = $viewOnly ?? false;
@endphp

{{-- ヘッダー部分 --}}
<div class="space-y-8">
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-amber-600 via-orange-600 to-yellow-600 p-[2px]">
        <div class="relative bg-white/90 backdrop-blur-xl rounded-2xl p-8">
            <div class="absolute inset-0 bg-gradient-to-r from-amber-50/50 to-yellow-50/50 animate-pulse"></div>
            <div class="relative">
                <h3 class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-amber-600 to-yellow-600">
                    スクリーンショット
                </h3>
                <p class="mt-3 text-lg text-gray-600">アプリの画面を最大3枚まで追加できます</p>
            </div>
        </div>
    </div>

    {{-- アップロードエリア --}}
    <div x-data="screenshotTab">
        <div class="space-y-8">
            @if(!$viewOnly)
                {{-- ドラッグ&ドロップエリア追加 --}}
                <div 
                    class="mt-4 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center"
                    @dragover.prevent
                    @drop.prevent="handleFiles($event.dataTransfer.files)"
                >
                    <p class="text-gray-600">
                        ここにファイルをドラッグ&ドロップするか、
                    </p>
                    <input 
                        type="file" 
                        id="screenshot-upload"
                        class="hidden" 
                        accept="image/*" 
                        multiple
                        @change="handleFiles($event.target.files)"
                    >
                    <label 
                        for="screenshot-upload"
                        class="inline-block px-4 py-2 bg-blue-500 text-white rounded cursor-pointer hover:bg-blue-600 mt-2"
                    >
                        スクリーンショットを追加
                    </label>
                </div>
            @endif

            {{-- プレビュー表示 --}}
            <div class="mt-4 space-y-4">
                <template x-for="screenshot in screenshots" :key="screenshot.url">
                    <div class="relative">
                        <img 
                            :src="screenshot.url" 
                            class="max-w-full h-auto rounded-lg"
                            alt="アプリのスクリーンショット"
                        >
                        @if(!$viewOnly)
                            <button 
                                @click="removeScreenshot(screenshot)"
                                class="absolute top-2 right-2 bg-red-500 text-white p-2 rounded-full hover:bg-red-600"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

{{-- スクリーンショットモーダル --}}
<div
    x-data="{ 
        show: false,
        imageSrc: '',
        init() {
            window.addEventListener('open-app-screenshot-modal', (e) => {
                this.imageSrc = e.detail.src;
                this.show = true;
            });
        }
    }"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 overflow-hidden"
    style="background-color: rgba(0, 0, 0, 0.75);"
    @click.self="show = false"
    @keydown.escape.window="show = false"
>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative">
            {{-- 閉じるボタン --}}
            <button 
                @click="show = false"
                class="absolute -top-4 -right-4 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg transition-all duration-200 z-50"
            >×</button>

            {{-- 画像 --}}
            <img
                :src="imageSrc"
                class="max-w-[50vw] max-h-[50vh] object-contain rounded-lg shadow-xl"
                alt="拡大画像"
            >
        </div>
    </div>
</div>

{{-- Alpine.js スクリプト --}}
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('screenshotTab', () => ({
        screenshots: [],
        
        init() {
            // 初期データの読み込み
            if (window.formData && window.formData.screenshots) {
                this.screenshots = [...window.formData.screenshots];
            }

            // フォームデータの更新を監視
            window.addEventListener('formDataUpdated', () => {
                if (window.formData && window.formData.screenshots) {
                    this.screenshots = [...window.formData.screenshots];
                }
            });
        },

        async handleFiles(files) {
            const maxFiles = 3;
            const maxSize = 5 * 1024 * 1024; // 5MB

            if (this.screenshots.length >= maxFiles) {
                this.showToast('スクリーンショットは最大3枚までです。', 'error');
                return;
            }

            for (const file of Array.from(files)) {
                try {
                    // バリデーション
                    if (file.size > maxSize) {
                        throw new Error(`${file.name}は5MB以上です`);
                    }

                    // FormData作成とアップロード処理
                    const formData = new FormData();
                    formData.append('screenshot', file);

                    const response = await fetch('/api/v2/screenshots/upload', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const result = await response.json();

                    if (!result.success) {
                        throw new Error(result.message || 'アップロードに失敗しました');
                    }

                    // window.formDataの初期化確認
                    if (!window.formData) {
                        window.formData = { screenshots: [] };
                    }

                    // スクリーンショット配列に追加
                    this.screenshots.push({
                        public_id: result.public_id,
                        url: result.url
                    });

                    // フォームデータの更新
                    window.formData.screenshots = [...this.screenshots];
                    window.dispatchEvent(new CustomEvent('formDataUpdated'));

                    this.showToast('スクリーンショットを保存しました');
                    
                    if (typeof window.autoSave === 'function') {
                        await window.autoSave();
                    }

                } catch (error) {
                    console.error('Upload error:', error);
                    this.showToast(error.message, 'error');
                }
            }
        },

        async removeScreenshot(screenshot) {
            if (confirm('この画像を削除してもよろしいですか？')) {
                try {
                    const response = await fetch('/api/v2/screenshots/delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ public_id: screenshot.public_id })
                    });

                    if (!response.ok) throw new Error('削除に失敗しました');

                    const index = this.screenshots.findIndex(s => s.url === screenshot.url);
                    if (index > -1) {
                        this.screenshots.splice(index, 1);
                        window.formData.screenshots = [...this.screenshots];
                        
                        // フォームデータの更新後にイベント発火
                        window.dispatchEvent(new CustomEvent('formDataUpdated'));

                        this.showToast('スクリーンショットを削除しました');
                        if (typeof window.autoSave === 'function') {
                            await window.autoSave();
                        }
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                    this.showToast(error.message, 'error');
                }
            }
        },

        showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white px-6 py-3 rounded-lg shadow-lg transition-opacity duration-500`;
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }
    }));
});
</script> 