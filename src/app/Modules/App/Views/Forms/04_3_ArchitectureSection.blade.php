<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">アーキテクチャ</h2>

    <!-- アーキテクチャパターン -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            アーキテクチャパターン
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'mvc' => 'MVC',
                'mvvm' => 'MVVM',
                'clean' => 'クリーンアーキテクチャ',
                'layered' => 'レイヤードアーキテクチャ',
                'ddd' => 'ドメイン駆動設計',
                'microservices' => 'マイクロサービス',
                'serverless' => 'サーバーレス',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="radio"
                        name="architecture_pattern"
                        value="{{ $value }}"
                        {{ old('architecture_pattern', $app->architecture_pattern ?? '') == $value ? 'checked' : '' }}
                        class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <!-- その他の場合の入力欄 -->
        <div class="mt-2">
            <input 
                type="text" 
                name="other_architecture" 
                id="other_architecture"
                value="{{ old('other_architecture', $app->other_architecture ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のアーキテクチャパターンを入力"
            >
        </div>
        @error('architecture_pattern')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 設計パターン -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            使用している設計パターン
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'singleton' => 'Singleton',
                'factory' => 'Factory',
                'observer' => 'Observer',
                'strategy' => 'Strategy',
                'repository' => 'Repository',
                'decorator' => 'Decorator',
                'facade' => 'Facade',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="design_patterns[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('design_patterns', $app->design_patterns ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <!-- その他の場合の入力欄 -->
        <div class="mt-2">
            <input 
                type="text" 
                name="other_patterns" 
                id="other_patterns"
                value="{{ old('other_patterns', $app->other_patterns ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他の設計パターンを入力"
            >
        </div>
        @error('design_patterns')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- アーキテクチャの説明 -->
    <div class="mb-6">
        <label for="architecture_description" class="block text-sm font-medium text-gray-700">
            アーキテクチャの説明
        </label>
        <textarea 
            name="architecture_description" 
            id="architecture_description"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="アプリケーションのアーキテクチャについて、特徴や採用理由などを説明してください"
        >{{ old('architecture_description', $app->architecture_description ?? '') }}</textarea>
        @error('architecture_description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- ヒント -->
    <div class="mt-4 p-4 bg-gray-50 rounded-md">
        <h3 class="text-sm font-medium text-gray-700 mb-2">入力のヒント</h3>
        <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
            <li>全体的なアーキテクチャの構成</li>
            <li>採用した理由や背景</li>
            <li>特徴的な設計パターン</li>
            <li>モジュール間の依存関係</li>
            <li>データフローの概要</li>
        </ul>
    </div>
</div> 