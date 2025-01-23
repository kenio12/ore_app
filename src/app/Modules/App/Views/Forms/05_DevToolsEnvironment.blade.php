<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">開発ツール環境</h2>

    <!-- インフラストラクチャ/クラウドサービス -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            インフラストラクチャ/クラウドサービス
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'aws' => 'AWS',
                'gcp' => 'Google Cloud',
                'azure' => 'Microsoft Azure',
                'heroku' => 'Heroku',
                'vercel' => 'Vercel',
                'digitalocean' => 'DigitalOcean',
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
                name="other_infrastructure" 
                id="other_infrastructure"
                value="{{ old('other_infrastructure', $app->other_infrastructure ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のインフラ/クラウドサービスを入力"
                readonly
            >
        </div>
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
                'aws_codepipeline' => 'AWS CodePipeline',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="ci_cd[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('ci_cd', $app->ci_cd ?? [])) ? 'checked' : '' }}
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
                name="other_ci_cd" 
                id="other_ci_cd"
                value="{{ old('other_ci_cd', $app->other_ci_cd ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のCI/CDツールを入力"
                readonly
            >
        </div>
    </div>

    <!-- API開発ツール -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            API開発ツール
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'postman' => 'Postman',
                'swagger' => 'Swagger/OpenAPI',
                'insomnia' => 'Insomnia',
                'curl' => 'cURL',
                'graphql_playground' => 'GraphQL Playground',
                'apollo_studio' => 'Apollo Studio',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="api_tools[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('api_tools', $app->api_tools ?? [])) ? 'checked' : '' }}
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
                name="other_api_tools" 
                id="other_api_tools"
                value="{{ old('other_api_tools', $app->other_api_tools ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のAPI開発ツールを入力"
                readonly
            >
        </div>
    </div>

    <!-- コミュニケーション -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            コミュニケーション
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'slack' => 'Slack',
                'discord' => 'Discord',
                'teams' => 'Microsoft Teams',
                'zoom' => 'Zoom',
                'meet' => 'Google Meet',
                'chatwork' => 'Chatwork',
                'skype' => 'Skype',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="communication_tools[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('communication_tools', $app->communication_tools ?? [])) ? 'checked' : '' }}
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
                name="other_communication_tools" 
                id="other_communication_tools"
                value="{{ old('other_communication_tools', $app->other_communication_tools ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のコミュニケーションツールを入力"
                readonly
            >
        </div>
    </div>

    <!-- 監視・分析 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            監視・分析
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'datadog' => 'Datadog',
                'newrelic' => 'New Relic',
                'grafana' => 'Grafana',
                'prometheus' => 'Prometheus',
                'sentry' => 'Sentry',
                'cloudwatch' => 'AWS CloudWatch',
                'stackdriver' => 'Google Cloud Monitoring',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="monitoring_tools[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('monitoring_tools', $app->monitoring_tools ?? [])) ? 'checked' : '' }}
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
                name="other_monitoring_tools" 
                id="other_monitoring_tools"
                value="{{ old('other_monitoring_tools', $app->other_monitoring_tools ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他の監視・分析ツールを入力"
                readonly
            >
        </div>
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
            readonly
        >{{ old('tool_versions', $app->tool_versions ?? '') }}</textarea>
        @error('tool_versions')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div> 