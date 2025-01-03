<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">ソフトウェア環境</h2>

    <!-- OS -->
    <div class="mb-6">
        <label for="os" class="block text-sm font-medium text-gray-700">
            OS <span class="text-red-500">*</span>
        </label>
        <input 
            type="text" 
            name="os" 
            id="os"
            value="{{ old('os', $post->os ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：macOS Ventura 13.4"
            required
        >
        @error('os')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 開発環境 -->
    <div class="mb-6">
        <label for="development_environment" class="block text-sm font-medium text-gray-700">
            開発環境 <span class="text-red-500">*</span>
        </label>
        <textarea 
            name="development_environment" 
            id="development_environment"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：VSCode, Docker, Git"
            required
        >{{ old('development_environment', $post->development_environment ?? '') }}</textarea>
        @error('development_environment')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 主要な言語・フレームワークのバージョン -->
    <div class="mb-6">
        <label for="language_versions" class="block text-sm font-medium text-gray-700">
            主要な言語・フレームワークのバージョン <span class="text-red-500">*</span>
        </label>
        <textarea 
            name="language_versions" 
            id="language_versions"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
PHP 8.1
Laravel 10.0
Node.js 18.0"
            required
        >{{ old('language_versions', $post->language_versions ?? '') }}</textarea>
        @error('language_versions')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- その他のツール・ライブラリ -->
    <div class="mb-6">
        <label for="other_tools" class="block text-sm font-medium text-gray-700">
            その他のツール・ライブラリ
        </label>
        <textarea 
            name="other_tools" 
            id="other_tools"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
- Composer
- npm
- Git Flow"
        >{{ old('other_tools', $post->other_tools ?? '') }}</textarea>
        @error('other_tools')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div> 