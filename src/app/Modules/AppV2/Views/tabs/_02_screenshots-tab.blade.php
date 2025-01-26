@php
    // データの初期化
    $formData = $data ?? [];
    $app = $app ?? null;
    $viewOnly = $viewOnly ?? false;
@endphp

{{-- モーダルコンポーネントをインクルード --}}
@include('AppV2::components.screenshot-modal')

<div class="space-y-8" x-data="{ 
    screenshots: @json($formData['screenshots'] ?? []),
    handleFiles(files) {
        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) {
                alert('画像ファイルのみアップロード可能です');
                return;
            }
            const formData = new FormData();
            formData.append('screenshot', file);
            
            fetch('/api/v2/screenshots/upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    this.screenshots.push({
                        public_id: result.public_id,
                        url: result.url,
                        order: this.screenshots.length
                    });
                    this.$dispatch('screenshots-updated', this.screenshots);
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                alert('アップロードに失敗しました');
            });
        });
    },
    removeScreenshot(index) {
        const screenshot = this.screenshots[index];
        if (screenshot && screenshot.public_id) {
            fetch('/api/v2/screenshots/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({ public_id: screenshot.public_id })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    this.screenshots.splice(index, 1);
                    this.$dispatch('screenshots-updated', this.screenshots);
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                alert('削除に失敗しました');
            });
        }
    }
}">
    {{-- ヘッダー部分 --}}
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
    @if(!$viewOnly)
        <div class="mb-4">
            {{-- ドラッグ&ドロップエリア --}}
            <div
                class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition-colors duration-200"
                @dragover.prevent
                @drop.prevent="handleFiles($event.dataTransfer.files)"
            >
                <input 
                    type="file" 
                    id="screenshot-upload"
                    class="hidden" 
                    accept="image/*" 
                    multiple
                    @change="handleFiles($event.target.files)"
                >
                <div class="text-gray-600">
                    <p class="mb-2">ドラッグ&ドロップ</p>
                    <p>または</p>
                    <label 
                        for="screenshot-upload"
                        class="inline-block mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 cursor-pointer"
                    >
                        ファイルを選択
                    </label>
                </div>
            </div>
        </div>
    @endif

    {{-- 画像グリッド --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <template x-for="(screenshot, index) in screenshots" :key="index">
            <div class="relative group">
                <img 
                    :src="screenshot.url" 
                    class="w-full h-48 object-cover rounded-lg cursor-pointer"
                    @click="$dispatch('open-app-screenshot-modal', { src: screenshot.url })"
                >
                @if(!$viewOnly)
                    <button 
                        @click="removeScreenshot(index)"
                        class="absolute top-2 right-2 bg-red-500 text-white p-2 rounded-full opacity-0 group-hover:opacity-100"
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