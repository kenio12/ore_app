<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">アーキテクチャパターン</h2>

    <!-- アーキテクチャパターン -->
    <div class="mb-6">
        <label for="architecture_pattern" class="block text-sm font-medium text-gray-700">
            採用したアーキテクチャパターン <span class="text-red-500">*</span>
        </label>
        <select
            name="architecture_pattern"
            id="architecture_pattern"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            required
        >
            <option value="">選択してください</option>
            @foreach([
                'monolithic' => 'モノリシック',
                'microservices' => 'マイクロサービス',
                'layered' => 'レイヤードアーキテクチャ',
                'hexagonal' => 'ヘキサゴナルアーキテクチャ',
                'mvc' => 'MVC',
                'mvvm' => 'MVVM',
                'cqrs' => 'CQRS',
                'event_sourcing' => 'イベントソーシング',
                'modular_monolith' => 'モジュラーモノリス',
                'serverless' => 'サーバーレス',
                'other' => 'その他'
            ] as $value => $label)
                <option 
                    value="{{ $value }}"
                    {{ old('architecture_pattern', $app->architecture_pattern ?? '') == $value ? 'selected' : '' }}
                >
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('architecture_pattern')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 採用理由 -->
    <div class="mb-6">
        <label for="architecture_reason" class="block text-sm font-medium text-gray-700">
            採用理由 <span class="text-red-500">*</span>
        </label>
        <textarea 
            name="architecture_reason" 
            id="architecture_reason"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="このアーキテクチャパターンを選んだ理由を説明してください"
            required
        >{{ old('architecture_reason', $app->architecture_reason ?? '') }}</textarea>
        @error('architecture_reason')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 実装の工夫 -->
    <div class="mb-6">
        <label for="architecture_details" class="block text-sm font-medium text-gray-700">
            実装の工夫点
        </label>
        <textarea 
            name="architecture_details" 
            id="architecture_details"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="アーキテクチャの実装で工夫した点があれば記述してください"
        >{{ old('architecture_details', $app->architecture_details ?? '') }}</textarea>
        @error('architecture_details')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div> 