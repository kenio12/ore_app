@php
    // データの初期化
    $formData = $data ?? [];
    $app = $app ?? null;
    $viewOnly = $viewOnly ?? false;
@endphp

<div class="space-y-8">
    {{-- ヘッダー --}}
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
    <div class="bg-white p-8 rounded-lg shadow mb-8">
        <div class="mb-6">
            @if($viewOnly ?? false)
                {{-- 表示モード --}}
                <div id="preview-container" class="mt-4 space-y-4">
                    @if(isset($app) && isset($app->screenshots) && is_array($app->screenshots))
                        @foreach($app->screenshots as $screenshot)
                            <div class="relative block">
                                <img 
                                    src="{{ $screenshot['url'] }}" 
                                    style="min-height: 80vh; max-height: 70vh; width: auto; object-fit: contain;"
                                    class="mx-auto block cursor-pointer rounded-lg"
                                    onclick="window.dispatchEvent(new CustomEvent('open-app-screenshot-modal', {detail: {src: this.src}}))"
                                    alt="アプリのスクリーンショット"
                                >
                            </div>
                        @endforeach
                    @endif
                </div>
            @else
                {{-- アップロードフォーム --}}
                <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4-4m4-4l4-4m-4-4l4-4m-4 4l4 4m-4 4l4 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="screenshots" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>ファイルを選択</span>
                                <input 
                                    type="file"
                                    id="screenshots"
                                    name="screenshots[]"
                                    accept="image/*"
                                    multiple
                                    class="sr-only"
                                >
                            </label>
                            <p class="pl-1">またはドラッグ＆ドロップ</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                    </div>
                </div>

                {{-- プレビュー表示 --}}
                <div id="preview-container" class="mt-4 space-y-4">
                    @if(isset($app) && !empty($app->screenshots) && is_array($app->screenshots))
                        @foreach($app->screenshots as $screenshot)
                            <div class="relative block">
                                <img 
                                    src="{{ $screenshot['url'] ?? '' }}" 
                                    style="min-height: 80vh; max-height: 70vh; width: auto; object-fit: contain;"
                                    class="mx-auto block cursor-pointer rounded-lg"
                                    onclick="window.dispatchEvent(new CustomEvent('open-app-screenshot-modal', {detail: {src: this.src}}))"
                                    alt="アプリのスクリーンショット"
                                >
                                {{-- 既存画像のURL情報を保持 --}}
                                <input type="hidden" name="existing_screenshots[]" value="{{ json_encode($screenshot) }}">
                                <button 
                                    type="button"
                                    onclick="this.parentElement.remove()"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center"
                                >×</button>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endif

            @error('screenshots')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('screenshots');
    if (!input) return;

    const previewContainer = document.getElementById('preview-container');
    const maxFiles = 3;
    const maxSize = 5 * 1024 * 1024; // 5MB

    async function handleFiles(files) {
        const currentImages = previewContainer.querySelectorAll('.relative').length;
        
        if (currentImages >= maxFiles) {
            alert('スクリーンショットは最大3枚までです。');
            return;
        }

        for (const file of Array.from(files)) {
            let loadingDiv = null;  // ここで定義
            try {
                // バリデーション
                if (file.size > maxSize) {
                    alert(`${file.name}は5MB以上です`);
                    continue;
                }

                if (!file.type.startsWith('image/')) {
                    alert(`${file.name}は画像ファイルではありません`);
                    continue;
                }

                // FormData作成
                const formData = new FormData();
                formData.append('screenshot', file);

                // ローディング表示
                loadingDiv = document.createElement('div');
                loadingDiv.className = 'relative block mb-4';
                loadingDiv.innerHTML = `
                    <div class="flex items-center justify-center h-[80vh] bg-gray-100 rounded-lg">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                    </div>
                `;
                previewContainer.appendChild(loadingDiv);

                // アップロード
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

                // プレビュー作成
                const imageContainer = document.createElement('div');
                imageContainer.className = 'relative block mb-4';
                imageContainer.innerHTML = `
                    <img 
                        src="${result.url}" 
                        style="min-height: 80vh; max-height: 70vh; width: auto; object-fit: contain;"
                        class="mx-auto block cursor-pointer rounded-lg"
                        onclick="window.dispatchEvent(new CustomEvent('open-app-screenshot-modal', {detail: {src: '${result.url}'}}))"
                        alt="アプリのスクリーンショット"
                    >
                    <input type="hidden" name="screenshots[]" value='${JSON.stringify({
                        public_id: result.public_id,
                        url: result.url
                    })}'>
                    <button 
                        type="button"
                        onclick="removeScreenshot(this.parentElement)"
                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center"
                    >×</button>
                `;
                previewContainer.appendChild(imageContainer);

            } catch (error) {
                console.error('Upload error:', error);
                alert('アップロードに失敗しました: ' + error.message);
            } finally {
                if (loadingDiv) {
                    loadingDiv.remove();
                }
            }
        }
    }

    // スクリーンショットの削除
    window.removeScreenshot = function(element) {
        if (confirm('この画像を削除してもよろしいですか？')) {
            element.remove();
        }
    };

    // ドラッグ&ドロップの処理
    const dropArea = input.closest('div.mt-2');
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropArea.classList.add('border-blue-500', 'bg-blue-50');
    }

    function unhighlight(e) {
        dropArea.classList.remove('border-blue-500', 'bg-blue-50');
    }

    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    input.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });
});
</script> 