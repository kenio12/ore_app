@php
    // データの初期化
    $formData = $data ?? [];
    $app = $app ?? null;
    $viewOnly = $viewOnly ?? false;
@endphp

<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">基本情報</h2>

    <!-- アプリ名 -->
    <div class="mb-6">
        <label for="title" class="block text-sm font-medium text-gray-700">
            アプリ名 <span class="text-red-500">*</span>
        </label>
        <input 
            type="text" 
            name="title" 
            id="title"
            value="{{ old('title', $app->title ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            {{ $viewOnly ?? false ? 'disabled' : 'required' }}
        >
        @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- アプリの紹介 -->
    <div class="mb-6">
        <label for="description" class="block text-sm font-medium text-gray-700">
            アプリの紹介 <span class="text-red-500">*</span>
        </label>
        <textarea 
            name="description" 
            id="description"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="あなたのアプリの特徴や魅力を簡潔に説明してください"
            {{ $viewOnly ?? false ? 'disabled' : 'required' }}
        >{{ old('description', $app->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- スクリーンショット -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">
            スクリーンショット（1〜3枚） <span class="text-red-500">*</span>
        </label>

        @if($viewOnly ?? false)
            <div class="mt-1 space-y-6">
                @if(isset($app->screenshots) && is_array($app->screenshots))
                    @foreach($app->screenshots as $index => $screenshot)
                        <div class="screenshot-item">
                            <div class="text-center mb-2">
                                <p class="text-sm text-gray-600">スクリーンショット {{ $index + 1 }}</p>
                            </div>
                            
                            <div class="flex justify-center">
                                <img src="{{ $screenshot['url'] }}" 
                                     alt="Screenshot {{ $index + 1 }}"
                                     style="height: 80vh; width: auto;"
                                     class="max-w-4xl object-contain rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-200 cursor-pointer"
                                     onclick="openImageModal(this.src)">
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-gray-500 py-2">
                        スクリーンショットはまだ登録されていません
                    </div>
                @endif
            </div>
        @else
            <div class="mt-2" x-data="{ isDragging: false }">
                <div id="preview-container" class="mb-4">
                    @if(isset($app->screenshots) && is_array($app->screenshots))
                        @foreach($app->screenshots as $index => $screenshot)
                            @if(isset($screenshot['url']))
                                <div class="relative block mb-4">
                                    <img src="{{ $screenshot['url'] }}" 
                                         class="max-w-full h-auto rounded-lg mx-auto block">
                                    <button type="button"
                                            class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center"
                                            onclick="removeScreenshot(this)">×</button>
                                    <input type="hidden" name="existing_screenshots[]" value="{{ $index }}">
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>

                <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-md"
                    x-bind:class="{ 
                        'border-gray-300': !isDragging,
                        'border-blue-500 bg-blue-50': isDragging 
                    }"
                    x-on:dragover.prevent="handleDragOver($event)"
                    x-on:dragleave.prevent="handleDragLeave"
                    x-on:drop.prevent="handleDrop($event)"
                >
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="screenshots" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>ファイルを選択</span>
                                <input 
                                    type="file" 
                                    name="screenshots[]"
                                    id="screenshots"
                                    multiple
                                    accept="image/*"
                                    class="sr-only"
                                    @change="handleFileSelect($event)"
                                >
                            </label>
                            <p class="pl-1">またはドラッグ＆ドロップ</p>
                        </div>
                        <p class="text-xs text-gray-500">
                            PNG, JPG, GIF up to 10MB
                        </p>
                    </div>
                </div>
            </div>
            @error('screenshots')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        @endif
    </div>

    <!-- 公開状態 -->
    <div class="mb-6">
        <label for="status" class="block text-sm font-medium text-gray-700">
            このサイト内の公開状態 <span class="text-red-500">*</span>
        </label>
        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            <option value="">選択してください</option>
            @foreach(config('app-module.constants.status_options') as $value => $label)
                <option value="{{ $value }}" {{ old('status', $app->status ?? '') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- デモURL -->
    <div class="mb-6">
        <label for="demo_url" class="block text-sm font-medium text-gray-700">
            アプリへのアクセス <span class="text-red-500">*</span>
        </label>
        <input 
            type="url" 
            name="demo_url" 
            id="demo_url"
            value="{{ old('demo_url', $app->demo_url ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="https://your-demo-site.com"
            required
        >
        @error('demo_url')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- GitHubリポジトリURL -->
    <div class="mb-6">
        <label for="github_url" class="block text-sm font-medium text-gray-700">
            GitHubリポジトリURL <span class="text-red-500">*</span>
        </label>
        <input 
            type="url" 
            name="github_url" 
            id="github_url"
            value="{{ old('github_url', $app->github_url ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="https://github.com/username/repo"
            required
        >
        @error('github_url')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- アプリの状態 -->
    <div class="mb-6">
        <label for="app_status" class="block text-sm font-medium text-gray-700">
            アプリの状態 <span class="text-red-500">*</span>
        </label>
        <select 
            name="app_status"
            id="app_status"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            required
        >
            <option value="">選択してください</option>
            <option value="completed" {{ old('app_status', $app->app_status ?? '') == 'completed' ? 'selected' : '' }}>
                完成
            </option>
            <option value="in_development" {{ old('app_status', $app->app_status ?? '') == 'in_development' ? 'selected' : '' }}>
                開発中
            </option>
        </select>
        @error('app_status')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 開発期間 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-4">
            開発期間 <span class="text-red-500">*</span>
        </label>

        <!-- 開発開始日・終了日 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="development_start_date" class="block text-sm text-gray-600 mb-1">
                    開発開始日
                </label>
                <input 
                    type="date" 
                    name="development_start_date" 
                    id="development_start_date"
                    value="{{ old('development_start_date', $app->development_start_date?->format('Y-m-d') ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('development_start_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="development_end_date" class="block text-sm text-gray-600 mb-1">
                    開発終了日
                </label>
                <input 
                    type="date" 
                    name="development_end_date" 
                    id="development_end_date"
                    value="{{ old('development_end_date', $app->development_end_date?->format('Y-m-d') ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                @error('development_end_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- 開発期間（年月） -->
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <input 
                    type="number" 
                    name="development_period_years" 
                    id="development_period_years"
                    value="{{ old('development_period_years', $app->development_period_years ?? 0) }}"
                    class="mt-1 block w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    min="0"
                    required
                >
                <span class="text-gray-700">年</span>
            </div>
            <div class="flex items-center gap-2">
                <input 
                    type="number" 
                    name="development_period_months" 
                    id="development_period_months"
                    value="{{ old('development_period_months', $app->development_period_months ?? 0) }}"
                    class="mt-1 block w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    min="0"
                    max="11"
                    required
                >
                <span class="text-gray-700">ヶ月</span>
            </div>
        </div>
        @error('development_period_years')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        @error('development_period_months')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- アプリの種類 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            アプリの種類（複数選択可） <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach(config('app-module.constants.app_types') as $value => $label)
                <div class="flex items-center gap-2">
                    @if($viewOnly ?? false)
                        <div class="{{ in_array($value, old('app_types', $app->app_types ?? [])) 
                            ? 'text-blue-600 font-medium bg-blue-50 py-1 px-2 rounded' 
                            : 'text-gray-400' }}">
                            {{ $label }}
                        </div>
                    @else
                        <label class="flex items-center gap-2">
                            <input
                                type="checkbox"
                                name="app_types[]"
                                value="{{ $value }}"
                                {{ in_array($value, old('app_types', $app->app_types ?? [])) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            >
                            <span class="text-gray-700">{{ $label }}</span>
                        </label>
                    @endif
                </div>
            @endforeach
        </div>
        @error('app_types')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- ジャンル -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            ジャンル（複数選択可） <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach(config('app-module.constants.genres') as $value => $label)
                <div class="flex items-center gap-2">
                    @if($viewOnly ?? false)
                        <div class="{{ in_array($value, old('genres', $app->genres ?? [])) 
                            ? 'text-blue-600 font-medium bg-blue-50 py-1 px-2 rounded' 
                            : 'text-gray-400' }}">
                            {{ $label }}
                        </div>
                    @else
                        <label class="flex items-center gap-2">
                            <input
                                type="checkbox"
                                name="genres[]"
                                value="{{ $value }}"
                                {{ in_array($value, old('genres', $app->genres ?? [])) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            >
                            <span class="text-gray-700">{{ $label }}</span>
                        </label>
                    @endif
                </div>
            @endforeach
        </div>
        @error('genres')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- デバッグ情報 -->
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<!-- スクリーンショットモーダル -->
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
            <!-- 閉じるボタン -->
            <button 
                @click="show = false"
                class="absolute -top-4 -right-4 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg transition-all duration-200 z-50"
            >×</button>

            <!-- 画像 -->
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
    let currentFiles = new DataTransfer(); // グローバルに保持

    // 既存の画像の削除処理を追加
    window.removeScreenshot = function(button) {
        const container = button.parentElement;
        container.remove();
    };

    input.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const existingPreviews = previewContainer.querySelectorAll('.relative').length;
        
        if (files.length + existingPreviews > maxFiles) {
            alert(`スクリーンショットは最大${maxFiles}枚までです`);
            return;
        }

        files.forEach(file => {
            if (file.size > maxSize) {
                alert(`${file.name}は5MB以上です`);
                return;
            }

            if (!file.type.startsWith('image/')) {
                alert(`${file.name}は画像ファイルではありません`);
                return;
            }

            // 有効なファイルをDataTransferに追加
            currentFiles.items.add(file);
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageContainer = document.createElement('div');
                imageContainer.className = 'relative block mb-4';
                imageContainer.dataset.fileName = file.name; // ファイル名を保存

                const previewImg = document.createElement('img');
                previewImg.src = e.target.result;
                previewImg.className = 'max-w-full h-auto rounded-lg mx-auto block';

                const deleteButton = document.createElement('button');
                deleteButton.innerHTML = '×';
                deleteButton.className = 'absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center';
                deleteButton.onclick = function() {
                    // DataTransferから該当ファイルを削除
                    const newDataTransfer = new DataTransfer();
                    Array.from(currentFiles.files).forEach(f => {
                        if (f.name !== file.name) {
                            newDataTransfer.items.add(f);
                        }
                    });
                    currentFiles = newDataTransfer;
                    input.files = currentFiles.files;
                    imageContainer.remove();
                };

                imageContainer.appendChild(previewImg);
                imageContainer.appendChild(deleteButton);
                previewContainer.appendChild(imageContainer);
            };
            reader.readAsDataURL(file);
        });

        // input要素のfilesを更新
        input.files = currentFiles.files;
    });
});
</script> 