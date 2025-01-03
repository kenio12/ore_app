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
            value="{{ old('title', $post->title ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            required
        >
        @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 公開状態 -->
    <div class="mb-6">
        <label for="publish_status" class="block text-sm font-medium text-gray-700">
            このサイト内の公開状態 <span class="text-red-500">*</span>
        </label>
        <select 
            name="publish_status" 
            id="publish_status"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            required
        >
            <option value="">選択してください</option>
            <option value="published" {{ old('publish_status', $post->publish_status ?? '') == 'published' ? 'selected' : '' }}>
                公開する
            </option>
            <option value="draft" {{ old('publish_status', $post->publish_status ?? '') == 'draft' ? 'selected' : '' }}>
                下書き（公開しない）
            </option>
        </select>
        @error('publish_status')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- デモURL -->
    <div class="mb-6">
        <label for="demo_url" class="block text-sm font-medium text-gray-700">
            アプリへのアクセス
        </label>
        <input 
            type="url" 
            name="demo_url" 
            id="demo_url"
            value="{{ old('demo_url', $post->demo_url ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="https://your-demo-site.com"
        >
        @error('demo_url')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- GitHubリポジトリURL -->
    <div class="mb-6">
        <label for="github_url" class="block text-sm font-medium text-gray-700">
            GitHubリポジトリURL
        </label>
        <input 
            type="url" 
            name="github_url" 
            id="github_url"
            value="{{ old('github_url', $post->github_url ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="https://github.com/username/repo"
        >
        @error('github_url')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- アプリの状態 -->
    <div class="mb-6">
        <label for="status" class="block text-sm font-medium text-gray-700">
            アプリの状態 <span class="text-red-500">*</span>
        </label>
        <select 
            name="status" 
            id="status"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            required
        >
            <option value="">選択してください</option>
            <option value="completed" {{ old('status', $post->status ?? '') == 'completed' ? 'selected' : '' }}>
                完成
            </option>
            <option value="in_development" {{ old('status', $post->status ?? '') == 'in_development' ? 'selected' : '' }}>
                開発中
            </option>
        </select>
        @error('status')
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
                    value="{{ old('development_period_years', $post->development_period_years ?? 0) }}"
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
                    value="{{ old('development_period_months', $post->development_period_months ?? 0) }}"
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

    <!-- ジャンル -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            ジャンル（複数選択可） <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($genres as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="genres[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('genres', $post->genres ?? [])) ? 'checked' : '' }}
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

    <!-- スクリーンショット -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            スクリーンショット（3枚まで）
        </label>
        <div 
            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md"
            x-data="{ isDragging: false }"
            x-on:dragover.prevent="isDragging = true"
            x-on:dragleave.prevent="isDragging = false"
            x-on:drop.prevent="isDragging = false"
            :class="{ 'border-blue-500 bg-blue-50': isDragging }"
        >
            <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
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
                            {{ count($post->screenshots ?? []) >= 3 ? 'disabled' : '' }}
                        >
                    </label>
                    <p class="pl-1">またはドラッグ&ドロップ</p>
                </div>
                <p class="text-xs text-gray-500">
                    3枚まで、1枚あたり5MB以下
                </p>
            </div>
        </div>

        <!-- プレビュー -->
        @if(isset($post->screenshots) && count($post->screenshots) > 0)
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($post->screenshots as $index => $url)
                    <div class="relative group">
                        <img 
                            src="{{ $url }}" 
                            alt="スクリーンショット{{ $index + 1 }}"
                            class="w-full h-48 object-cover rounded-lg"
                        >
                        <button
                            type="button"
                            onclick="removeScreenshot({{ $index }})"
                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

        @error('screenshots')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 説明 -->
    <div class="mb-6">
        <label for="description" class="block text-sm font-medium text-gray-700">
            このアプリの説明 <span class="text-red-500">*</span>
        </label>
        <textarea 
            name="description" 
            id="description"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            required
        >{{ old('description', $post->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

@push('scripts')
<script>
function removeScreenshot(index) {
    // スクリーンショット削除の処理をここに実装
    // 例: Ajaxリクエストを送信して削除
}
</script>
@endpush 