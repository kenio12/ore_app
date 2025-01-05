<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">その他の環境情報</h2>

    <!-- インフラストラクチャ -->
    <div class="mb-6">
        <label class="block mb-2 text-gray-700 font-medium">インフラストラクチャ</label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach([
                'AWS',
                'GCP',
                'Azure',
                'Heroku',
                'Vercel',
                'Render',
                'Firebase',
                'Cloudflare',
                'その他'
            ] as $option)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="other_info[infrastructure][]"
                        value="{{ $option }}"
                        {{ in_array($option, old('other_info.infrastructure', $app->other_info['infrastructure'] ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $option }}</span>
                </label>
            @endforeach
        </div>
        @error('other_info.infrastructure')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- CI/CD -->
    <div class="mb-6">
        <label class="block mb-2 text-gray-700 font-medium">CI/CD</label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach([
                'GitHub Actions',
                'CircleCI',
                'Jenkins',
                'GitLab CI',
                'Travis CI',
                'AWS CodePipeline',
                'その他'
            ] as $option)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="other_info[ci_cd][]"
                        value="{{ $option }}"
                        {{ in_array($option, old('other_info.ci_cd', $app->other_info['ci_cd'] ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $option }}</span>
                </label>
            @endforeach
        </div>
        @error('other_info.ci_cd')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- コンテナ技術 -->
    <div class="mb-6">
        <label class="block mb-2 text-gray-700 font-medium">コンテナ技術</label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach([
                'Docker',
                'Docker Compose',
                'Kubernetes',
                'その他'
            ] as $option)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="other_info[containers][]"
                        value="{{ $option }}"
                        {{ in_array($option, old('other_info.containers', $app->other_info['containers'] ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $option }}</span>
                </label>
            @endforeach
        </div>
        @error('other_info.containers')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- その他のツール -->
    <div class="mb-6">
        <label for="other_info_tools" class="block text-sm font-medium text-gray-700">
            その他のツール・サービス
        </label>
        <textarea 
            name="other_info[tools]" 
            id="other_info_tools"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="監視ツール、ログ管理、セキュリティツールなど"
        >{{ old('other_info.tools', $app->other_info['tools'] ?? '') }}</textarea>
        @error('other_info.tools')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- ヒント -->
    <div class="mt-4 p-4 bg-gray-50 rounded-md">
        <h3 class="text-sm font-medium text-gray-700 mb-2">入力のヒント</h3>
        <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
            <li>デプロイ環境とインフラ構成</li>
            <li>CI/CDパイプライン</li>
            <li>コンテナ化と環境構築</li>
            <li>監視とロギング</li>
            <li>セキュリティ対策</li>
        </ul>
    </div>
</div> 