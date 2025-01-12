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
            required
        >
        @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- スクリーンショット -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">
            スクリーンショット（1〜3枚） <span class="text-red-500">*</span>
        </label>
        <div 
            class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 rounded-md"
            id="dropzone"
        >
            <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-gray-600">
                    <label for="screenshots" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                        <span>画像をアップロード</span>
                        <input 
                            id="screenshots" 
                            name="screenshots[]" 
                            type="file" 
                            class="sr-only"
                            accept="image/*"
                            multiple
                            max="3"
                        >
                    </label>
                    <p class="pl-1">またはドラッグ＆ドロップ</p>
                </div>
                <p class="text-xs text-gray-500">
                    PNG, JPG, GIF 最大3枚まで（1枚5MB以下）
                </p>
            </div>
        </div>

        <!-- プレビュー領域 -->
        <div id="preview-container"></div>

        @error('screenshots')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        @error('screenshots.*')
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
            required
        >{{ old('description', $app->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 公開状態 -->
    <div class="mb-6">
        <label for="status" class="block text-sm font-medium text-gray-700">
            このサイト内の公開状態 <span class="text-red-500">*</span>
        </label>
        <select 
            name="status"
            id="status"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            required
        >
            <option value="">選択してください</option>
            <option value="published" {{ old('status', $app->status ?? '') == 'published' ? 'selected' : '' }}>
                公開する
            </option>
            <option value="draft" {{ old('status', $app->status ?? '') == 'draft' ? 'selected' : '' }}>
                下書き（公開しない）
            </option>
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
        <label for="development_period" class="block text-sm font-medium text-gray-700">
            開発期間 <span class="text-red-500">*</span>
        </label>
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
    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            アプリの種類 <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach([
                'web_app' => 'Webアプリケーション',
                'ios_app' => 'iOSアプリ',
                'android_app' => 'Androidアプリ',
                'windows_app' => 'Windowsアプリ',
                'mac_app' => 'macOSアプリ',
                'linux_app' => 'Linuxアプリ',
                'game' => 'ゲーム',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="radio"
                        name="app_type"
                        value="{{ $value }}"
                        {{ old('app_type', $app->app_type ?? '') == $value ? 'checked' : '' }}
                        class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                        required
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        @error('app_type')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- ジャンル -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            ジャンル（複数選択可） <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'sns' => 'SNS',
                'netshop' => 'ネットショップ/EC',
                'matching' => 'マッチングサービス',
                'learning_service' => '学習サービス',
                'work' => '仕事効率化',
                'entertainment' => '娯楽',
                'daily_life' => '日常生活',
                'communication' => 'コミュニケーション',
                'healthcare' => 'ヘルスケア',
                'finance' => '金融',
                'news_media' => 'ニュース・メディア',
                'food' => '飲食・フード',
                'travel' => '旅行・観光',
                'real_estate' => '不動産',
                'education' => '教育',
                'recruitment' => '採用・求人',
                'literature' => '文学',
                'art' => '美術',
                'music' => '音楽',
                'pet' => 'ペット',
                'game' => 'ゲーム',
                'sports' => 'スポーツ',
                'academic' => '学問',
                'development_tool' => '開発ツール',
                'api_service' => 'API/Webサービス',
                'cms' => 'CMS',
                'blog' => 'ブログ/メディア',
                'portfolio' => 'ポートフォリオ',
                'other' => 'その他'
            ] as $value => $label)
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

<x-app::app-screenshot-modal />

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('screenshots');
    if (!input) return;

    const previewContainer = document.getElementById('preview-container');
    const maxFiles = 3;
    const maxSize = 5 * 1024 * 1024; // 5MB

    input.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        
        const existingPreviews = previewContainer.querySelectorAll('.relative').length;
        if (files.length + existingPreviews > maxFiles) {
            alert('スクリーンショットは最大3枚までです');
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

            const reader = new FileReader();
            reader.onload = function(e) {
                const imageContainer = document.createElement('div');
                imageContainer.className = 'relative block mb-4';

                const previewImg = document.createElement('img');
                previewImg.src = e.target.result;
                
                const tempImg = new Image();
                tempImg.src = e.target.result;
                
                tempImg.onload = function() {
                    const aspectRatio = this.width / this.height;
                    
                    if (aspectRatio < 1) {  // 縦長画像（スマホスクショ）
                        const viewportHeight = window.innerHeight;
                        const imageHeight = Math.floor(viewportHeight * 0.8);
                        
                        previewImg.style.cssText = `
                            height: ${imageHeight}px;
                            width: auto;
                            object-fit: contain;
                            border-radius: 0.5rem;
                            cursor: pointer;
                            margin: 0 auto;
                            display: block;
                        `;
                    } else {  // 横長画像（パソコンスクショ）
                        const viewportWidth = window.innerWidth;
                        const imageWidth = Math.floor(viewportWidth * 0.9);
                        
                        previewImg.style.cssText = `
                            width: ${imageWidth}px;
                            height: auto;
                            object-fit: contain;
                            border-radius: 0.5rem;
                            cursor: pointer;
                            margin: 0 auto;
                            display: block;
                        `;
                    }
                };
                
                previewImg.onclick = function() {
                    $dispatch('open-app-screenshot-modal', { src: this.src });
                };

                const deleteButton = document.createElement('button');
                deleteButton.innerHTML = '×';
                deleteButton.className = 'absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center';
                deleteButton.onclick = function() {
                    imageContainer.remove();
                };

                imageContainer.appendChild(previewImg);
                imageContainer.appendChild(deleteButton);
                previewContainer.appendChild(imageContainer);
            };
            reader.readAsDataURL(file);
        });
    });
});
</script> 