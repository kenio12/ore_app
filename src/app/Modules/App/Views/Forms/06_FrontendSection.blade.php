<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">フロントエンド環境</h2>

    <!-- プログラミング言語 -->
    <div class="mb-6">
        <label for="frontend_language" class="block text-sm font-medium text-gray-700">
            プログラミング言語 <span class="text-red-500">*</span>
        </label>
        <input 
            type="text" 
            name="frontend_language" 
            id="frontend_language"
            value="{{ old('frontend_language', $app->frontend_language ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：JavaScript, TypeScript"
            required
        >
        @error('frontend_language')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- フレームワーク -->
    <div class="mb-6">
        <label for="frontend_framework" class="block text-sm font-medium text-gray-700">
            フレームワーク <span class="text-red-500">*</span>
        </label>
        <input 
            type="text" 
            name="frontend_framework" 
            id="frontend_framework"
            value="{{ old('frontend_framework', $app->frontend_framework ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：Vue.js 3.0, React 18.0"
            required
        >
        @error('frontend_framework')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- CSSフレームワーク -->
    <div class="mb-6">
        <label for="css_framework" class="block text-sm font-medium text-gray-700">
            CSSフレームワーク <span class="text-red-500">*</span>
        </label>
        <input 
            type="text" 
            name="css_framework" 
            id="css_framework"
            value="{{ old('css_framework', $app->css_framework ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：TailwindCSS, Bootstrap"
            required
        >
        @error('css_framework')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 主要なライブラリ・パッケージ -->
    <div class="mb-6">
        <label for="frontend_packages" class="block text-sm font-medium text-gray-700">
            主要なライブラリ・パッケージ
        </label>
        <textarea 
            name="frontend_packages" 
            id="frontend_packages"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
- Axios (HTTP通信)
- Vuex (状態管理)
- Vue Router (ルーティング)"
        >{{ old('frontend_packages', $app->frontend_packages ?? '') }}</textarea>
        @error('frontend_packages')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div> 