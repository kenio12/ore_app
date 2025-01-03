<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">バックエンド環境</h2>

    <!-- プログラミング言語 -->
    <div class="mb-6">
        <label for="backend_language" class="block text-sm font-medium text-gray-700">
            プログラミング言語 <span class="text-red-500">*</span>
        </label>
        <input 
            type="text" 
            name="backend_language" 
            id="backend_language"
            value="{{ old('backend_language', $post->backend_language ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：PHP 8.1"
            required
        >
        @error('backend_language')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- ヒレームワーク -->
    <div class="mb-6">
        <label for="backend_framework" class="block text-sm font-medium text-gray-700">
            フレームワーク <span class="text-red-500">*</span>
        </label>
        <input 
            type="text" 
            name="backend_framework" 
            id="backend_framework"
            value="{{ old('backend_framework', $post->backend_framework ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：Laravel 10.0"
            required
        >
        @error('backend_framework')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 主要なライブラリ・パッケージ -->
    <div class="mb-6">
        <label for="backend_packages" class="block text-sm font-medium text-gray-700">
            主要なライブラリ・パッケージ <span class="text-red-500">*</span>
        </label>
        <textarea 
            name="backend_packages" 
            id="backend_packages"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
- Laravel Breeze (認証)
- Cloudinary PHP SDK (画像管理)
- PHPUnit (テスト)"
            required
        >{{ old('backend_packages', $post->backend_packages ?? '') }}</textarea>
        @error('backend_packages')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- API・外部サービス -->
    <div class="mb-6">
        <label for="backend_services" class="block text-sm font-medium text-gray-700">
            利用したAPI・外部サービス
        </label>
        <textarea 
            name="backend_services" 
            id="backend_services"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
- Cloudinary API
- SendGrid
- Stripe"
        >{{ old('backend_services', $post->backend_services ?? '') }}</textarea>
        @error('backend_services')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div> 