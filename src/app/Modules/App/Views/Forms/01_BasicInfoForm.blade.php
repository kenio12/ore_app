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
            <option value="published" {{ old('publish_status', $app->publish_status ?? '') == 'published' ? 'selected' : '' }}>
                公開する
            </option>
            <option value="draft" {{ old('publish_status', $app->publish_status ?? '') == 'draft' ? 'selected' : '' }}>
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
            value="{{ old('demo_url', $app->demo_url ?? '') }}"
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
            value="{{ old('github_url', $app->github_url ?? '') }}"
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
            <option value="completed" {{ old('status', $app->status ?? '') == 'completed' ? 'selected' : '' }}>
                完成
            </option>
            <option value="in_development" {{ old('status', $app->status ?? '') == 'in_development' ? 'selected' : '' }}>
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

    <!-- ジャンル -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            ジャンル（複数選択可） <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach([
                'web_app' => 'Webアプリケーション',
                'mobile_app' => 'モバイルアプリ',
                'desktop_app' => 'デスクトップアプリ',
                'game' => 'ゲーム',
                'tool' => '開発ツール',
                'api' => 'API/Webサービス',
                'cms' => 'CMS',
                'e_commerce' => 'ECサイト',
                'sns' => 'SNS',
                'netshop' => 'ネットショップ',
                'matching' => 'マッチングサービス',
                'learning_service' => '学習サービス',
                'work' => '仕事',
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
</div> 