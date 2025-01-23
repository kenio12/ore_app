<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">バックエンド環境</h2>

    <!-- プログラミング言語 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            プログラミング言語
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'php' => 'PHP',
                'python' => 'Python',
                'ruby' => 'Ruby',
                'java' => 'Java',
                'csharp' => 'C#',
                'golang' => 'Go',
                'nodejs' => 'Node.js',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="backend_languages[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('backend_languages', $app->backend_languages ?? [])) ? 'checked' : '' }}
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
                name="other_backend_language" 
                id="other_backend_language"
                value="{{ old('other_backend_language', $app->other_backend_language ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他の言語を入力"
                readonly
            >
        </div>
        @error('backend_languages')
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
                'laravel' => 'Laravel',
                'symfony' => 'Symfony',
                'django' => 'Django',
                'flask' => 'Flask',
                'rails' => 'Ruby on Rails',
                'spring' => 'Spring',
                'express' => 'Express',
                'dotnet' => '.NET',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="backend_frameworks[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('backend_frameworks', $app->backend_frameworks ?? [])) ? 'checked' : '' }}
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
                name="other_backend_framework" 
                id="other_backend_framework"
                value="{{ old('other_backend_framework', $app->other_backend_framework ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のフレームワークを入力"
                readonly
            >
        </div>
        @error('backend_frameworks')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 主要なライブラリ・パッケージ -->
    <div class="mb-6">
        <label for="backend_packages" class="block text-sm font-medium text-gray-700">
            主要なライブラリ・パッケージ
        </label>
        <textarea 
            name="backend_packages" 
            id="backend_packages"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
- JWT認証
- ORM
- キャッシュ
- メール送信
- 画像処理"
            readonly
        >{{ old('backend_packages', $app->backend_packages ?? '') }}</textarea>
        @error('backend_packages')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- バージョン情報 -->
    <div class="mb-6">
        <label for="backend_versions" class="block text-sm font-medium text-gray-700">
            バージョン情報
        </label>
        <textarea 
            name="backend_versions" 
            id="backend_versions"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            readonly
        >{{ old('backend_versions', $app->backend_versions ?? '') }}</textarea>
        @error('backend_versions')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div> 