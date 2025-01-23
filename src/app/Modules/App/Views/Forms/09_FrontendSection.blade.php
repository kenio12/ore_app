<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">フロントエンド環境</h2>

    <!-- プログラミング言語 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            プログラミング言語
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'javascript' => 'JavaScript',
                'typescript' => 'TypeScript',
                'html' => 'HTML',
                'css' => 'CSS',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="frontend_languages[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('frontend_languages', $app->frontend_languages ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        disabled
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <!-- その他の場合の入力欄 -->
        <div class="mt-2">
            <input 
                type="text" 
                name="other_frontend_language" 
                id="other_frontend_language"
                value="{{ old('other_frontend_language', $app->other_frontend_language ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他の言語を入力"
                readonly
            >
        </div>
        @error('frontend_languages')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- フレームワーク -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            フレームワーク
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'react' => 'React',
                'vue' => 'Vue.js',
                'angular' => 'Angular',
                'svelte' => 'Svelte',
                'next' => 'Next.js',
                'nuxt' => 'Nuxt.js',
                'jquery' => 'jQuery',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="frontend_frameworks[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('frontend_frameworks', $app->frontend_frameworks ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        disabled
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <!-- その他の場合の入力欄 -->
        <div class="mt-2">
            <input 
                type="text" 
                name="other_frontend_framework" 
                id="other_frontend_framework"
                value="{{ old('other_frontend_framework', $app->other_frontend_framework ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のフレームワークを入力"
                readonly
            >
        </div>
        @error('frontend_frameworks')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- CSSフレームワーク -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            CSSフレームワーク
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'tailwind' => 'Tailwind CSS',
                'bootstrap' => 'Bootstrap',
                'material' => 'Material UI',
                'chakra' => 'Chakra UI',
                'bulma' => 'Bulma',
                'sass' => 'Sass/SCSS',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="css_frameworks[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('css_frameworks', $app->css_frameworks ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        disabled
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <!-- その他の場合の入力欄 -->
        <div class="mt-2">
            <input 
                type="text" 
                name="other_css_framework" 
                id="other_css_framework"
                value="{{ old('other_css_framework', $app->other_css_framework ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のCSSフレームワークを入力"
                readonly
            >
        </div>
        @error('css_frameworks')
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
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
- 状態管理（Redux, Vuex等）
- ルーティング
- UIコンポーネント
- フォーム管理
- データ取得（Axios等）"
        >{{ old('frontend_packages', $app->frontend_packages ?? '') }}</textarea>
        @error('frontend_packages')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- バージョン情報 -->
    <div class="mb-6">
        <label for="frontend_versions" class="block text-sm font-medium text-gray-700">
            バージョン情報
        </label>
        <textarea 
            name="frontend_versions" 
            id="frontend_versions"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
Node.js 18.0
Vue.js 3.0
Tailwind CSS 3.0"
        >{{ old('frontend_versions', $app->frontend_versions ?? '') }}</textarea>
        @error('frontend_versions')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div> 