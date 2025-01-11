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

    <!-- API開発 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            API開発
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'postman' => 'Postman',
                'insomnia' => 'Insomnia',
                'thunder_client' => 'Thunder Client',
                'swagger' => 'Swagger',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="api_tools[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('api_tools', $app->api_tools ?? [])) ? 'checked' : '' }}
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
                name="other_api_tools" 
                id="other_api_tools"
                value="{{ old('other_api_tools', $app->other_api_tools ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のAPI開発ツールを入力"
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
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="communication_tools[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('communication_tools', $app->communication_tools ?? [])) ? 'checked' : '' }}
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
                name="other_communication_tools" 
                id="other_communication_tools"
                value="{{ old('other_communication_tools', $app->other_communication_tools ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のコミュニケーションツールを入力"
            >
        </div>
    </div>

    <!-- メール配信 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            メール配信
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'sendgrid' => 'SendGrid',
                'mailgun' => 'Mailgun',
                'ses' => 'Amazon SES',
                'mailtrap' => 'Mailtrap',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="mail_services[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('mail_services', $app->mail_services ?? [])) ? 'checked' : '' }}
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
                name="other_mail_services" 
                id="other_mail_services"
                value="{{ old('other_mail_services', $app->other_mail_services ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のメール配信ツールを入力"
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
                'sentry' => 'Sentry',
                'new_relic' => 'New Relic',
                'datadog' => 'Datadog',
                'ga' => 'Google Analytics',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="monitoring_tools[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('monitoring_tools', $app->monitoring_tools ?? [])) ? 'checked' : '' }}
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
                name="other_monitoring_tools" 
                id="other_monitoring_tools"
                value="{{ old('other_monitoring_tools', $app->other_monitoring_tools ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他の監視・分析ツールを入力"
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
        >{{ old('tool_versions', $app->tool_versions ?? '') }}</textarea>
        @error('tool_versions')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div> 