<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">その他の環境・ツール</h2>

    <!-- バージョン管理 -->
    <div class="mb-6">
        <label for="version_control" class="block text-sm font-medium text-gray-700">
            バージョン管理 <span class="text-red-500">*</span>
        </label>
        <input 
            type="text" 
            name="version_control" 
            id="version_control"
            value="{{ old('version_control', $post->version_control ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：Git / GitHub"
            required
        >
        @error('version_control')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- CI/CD -->
    <div class="mb-6">
        <label for="ci_cd" class="block text-sm font-medium text-gray-700">
            CI/CD
        </label>
        <input 
            type="text" 
            name="ci_cd" 
            id="ci_cd"
            value="{{ old('ci_cd', $post->ci_cd ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：GitHub Actions"
        >
        @error('ci_cd')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- コンテナ技術 -->
    <div class="mb-6">
        <label for="container" class="block text-sm font-medium text-gray-700">
            コンテナ技術
        </label>
        <input 
            type="text" 
            name="container" 
            id="container"
            value="{{ old('container', $post->container ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：Docker, Docker Compose"
        >
        @error('container')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- その他のツール -->
    <div class="mb-6">
        <label for="other_tools" class="block text-sm font-medium text-gray-700">
            その他のツール・サービス
        </label>
        <textarea 
            name="other_tools" 
            id="other_tools"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
- Postman (API開発)
- Slack (コミュニケーション)
- Trello (タスク管理)"
        >{{ old('other_tools', $post->other_tools ?? '') }}</textarea>
        @error('other_tools')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div> 