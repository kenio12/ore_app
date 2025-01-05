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

    <!-- アプリの紹介 -->
    <div class="mb-6">
        <label for="description" class="block text-sm font-medium text-gray-700">
            アプリの紹介 <span class="text-red-500">*</span>
        </label>
        <textarea 
            name="description" 
            id="description"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="あなたのアプリの特徴や魅力を簡潔に説明してください"
            required
        >{{ old('description', $app->description ?? '') }}</textarea>
        @error('description')
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
            アプリへのアクセス <span class="text-red-500">*</span>
        </label>
        <input 
            type="url" 
            name="demo_url" 
            id="demo_url"
            value="{{ old('demo_url', $app->demo_url ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="https://your-demo-site.com"
            required
        >
        @error('demo_url')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- GitHubリポジトリURL -->
    <div class="mb-6">
        <label for="github_url" class="block text-sm font-medium text-gray-700">
            GitHubリポジトリURL <span class="text-red-500">*</span>
        </label>
        <input 
            type="url" 
            name="github_url" 
            id="github_url"
            value="{{ old('github_url', $app->github_url ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="https://github.com/username/repo"
            required
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

    <!-- アプリの種類 -->
    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            アプリの種類 <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach([
                'web_app' => 'Webアプリケーション',
                'ios_app' => 'iOSアプリ',
                'android_app' => 'Androidアプリ',
                'windows_app' => 'Windowsアプリ',
                'mac_app' => 'macOSアプリ',
                'linux_app' => 'Linuxアプリ',
                'game' => 'ゲーム',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="radio"
                        name="app_type"
                        value="{{ $value }}"
                        {{ old('app_type', $app->app_type ?? '') == $value ? 'checked' : '' }}
                        class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                        required
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        @error('app_type')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- ジャンル -->
    <div class="mb-6 bg-white p-4 rounded-lg border border-gray-200">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            ジャンル（複数選択可） <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach([
                'sns' => 'SNS',
                'netshop' => 'ネットショップ/EC',
                'matching' => 'マッチングサービス',
                'learning_service' => '学習サービス',
                'work' => '仕事効率化',
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
                'development_tool' => '開発ツール',
                'api_service' => 'API/Webサービス',
                'cms' => 'CMS',
                'blog' => 'ブログ/メディア',
                'portfolio' => 'ポートフォリオ',
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