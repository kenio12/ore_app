<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">開発ツール環境</h2>

    <!-- インフラストラクチャ -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            インフラストラクチャ
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'aws' => 'AWS',
                'gcp' => 'GCP',
                'azure' => 'Azure',
                'heroku' => 'Heroku',
                'vercel' => 'Vercel',
                'render' => 'Render',
                'firebase' => 'Firebase',
                'cloudflare' => 'Cloudflare',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="infrastructure[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('infrastructure', $app->infrastructure ?? [])) ? 'checked' : '' }}
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
                name="other_infrastructure" 
                id="other_infrastructure"
                value="{{ old('other_infrastructure', $app->other_infrastructure ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のインフラを入力"
            >
        </div>
        @error('infrastructure')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- CI/CD -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            CI/CD
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'github_actions' => 'GitHub Actions',
                'gitlab_ci' => 'GitLab CI',
                'jenkins' => 'Jenkins',
                'circle_ci' => 'CircleCI',
                'travis_ci' => 'Travis CI',
                'azure_pipelines' => 'Azure Pipelines',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="ci_cd_tools[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('ci_cd_tools', $app->ci_cd_tools ?? [])) ? 'checked' : '' }}
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
                name="other_ci_cd" 
                id="other_ci_cd"
                value="{{ old('other_ci_cd', $app->other_ci_cd ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のCI/CDツールを入力"
            >
        </div>
        @error('ci_cd_tools')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- コンテナ技術 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            コンテナ技術
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'docker' => 'Docker',
                'docker_compose' => 'Docker Compose',
                'kubernetes' => 'Kubernetes',
                'podman' => 'Podman',
                'containerd' => 'containerd',
                'crio' => 'CRI-O',
                'buildah' => 'Buildah',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="container_tools[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('container_tools', $app->container_tools ?? [])) ? 'checked' : '' }}
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
                name="other_container" 
                id="other_container"
                value="{{ old('other_container', $app->other_container ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のコンテナ技術を入力"
            >
        </div>
        @error('container_tools')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- ストレージサービス -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            ストレージサービス
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'cloudinary' => 'Cloudinary',
                'aws_s3' => 'AWS S3',
                'gcs' => 'Google Cloud Storage',
                'firebase_storage' => 'Firebase Storage',
                'azure_blob' => 'Azure Blob Storage',
                'local' => 'ローカルストレージ',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="storage_services[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('storage_services', $app->storage_services ?? [])) ? 'checked' : '' }}
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
                name="other_storage_service" 
                id="other_storage_service"
                value="{{ old('other_storage_service', $app->other_storage_service ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のストレージサービスを入力"
            >
        </div>
        @error('storage_services')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- ビルドツール -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            ビルドツール
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'vite' => 'Vite',
                'webpack' => 'Webpack',
                'rollup' => 'Rollup',
                'parcel' => 'Parcel',
                'gulp' => 'Gulp',
                'grunt' => 'Grunt',
                'esbuild' => 'esbuild',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="build_tools[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('build_tools', $app->build_tools ?? [])) ? 'checked' : '' }}
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
                name="other_build_tool" 
                id="other_build_tool"
                value="{{ old('other_build_tool', $app->other_build_tool ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のビルドツールを入力"
            >
        </div>
        @error('build_tools')
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
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
- Postman (API開発)
- Slack (コミュニケーション)
- Trello (タスク管理)
- Sentry (エラー監視)
- New Relic (パフォーマンス監視)"
        >{{ old('other_tools', $app->other_tools ?? '') }}</textarea>
        @error('other_tools')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- バージョン情報 -->
    <div class="mb-6">
        <label for="tool_versions" class="block text-sm font-medium text-gray-700">
            バージョン情報
        </label>
        <textarea 
            name="tool_versions" 
            id="tool_versions"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="例：
Git 2.34.1
Docker 24.0.5
Docker Compose 2.20.2"
        >{{ old('tool_versions', $app->tool_versions ?? '') }}</textarea>
        @error('tool_versions')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- インフラストラクチャ/クラウドサービス -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            インフラストラクチャ/クラウドサービス
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'aws' => 'AWS',
                'gcp' => 'Google Cloud',
                'azure' => 'Azure',
                'heroku' => 'Heroku',
                'vercel' => 'Vercel',
                'render' => 'Render',
                'firebase' => 'Firebase',
                'cloudflare' => 'Cloudflare',
                'digitalocean' => 'DigitalOcean',
                'linode' => 'Linode',
                'vultr' => 'Vultr',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="cloud_services[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('cloud_services', $app->cloud_services ?? [])) ? 'checked' : '' }}
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
                name="other_cloud_service" 
                id="other_cloud_service"
                value="{{ old('other_cloud_service', $app->other_cloud_service ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のクラウドサービスを入力"
            >
        </div>
        @error('cloud_services')
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