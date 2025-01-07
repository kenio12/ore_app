<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">セキュリティと品質管理</h2>

    <!-- セキュリティ対策 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            セキュリティ対策
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'auth_jwt' => 'JWT認証',
                'auth_session' => 'セッション認証',
                'auth_oauth' => 'OAuth/SSO',
                'xss' => 'XSS対策',
                'csrf' => 'CSRF対策',
                'sql_injection' => 'SQLインジェクション対策',
                'rate_limit' => 'レート制限',
                'firewall' => 'ファイアウォール',
                'security_headers' => 'セキュリティヘッダー',
                'rbac' => 'ロールベースアクセス制御',
                'encryption' => 'データ暗号化',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="security_measures[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('security_measures', $app->security_measures ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <!-- パフォーマンス最適化 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            パフォーマンス最適化
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'caching' => 'キャッシュ戦略',
                'image_optimization' => '画像最適化',
                'cdn' => 'CDN利用',
                'db_index' => 'DBインデックス',
                'query_optimization' => 'クエリ最適化',
                'async_processing' => '非同期処理',
                'load_balancing' => '負荷分散',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="performance_optimizations[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('performance_optimizations', $app->performance_optimizations ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <!-- テスト環境 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            テスト環境
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'unit_test' => 'ユニットテスト',
                'integration_test' => '統合テスト',
                'e2e_test' => 'E2Eテスト',
                'phpunit' => 'PHPUnit',
                'jest' => 'Jest',
                'cypress' => 'Cypress',
                'coverage' => 'カバレッジ測定',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="testing_tools[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('testing_tools', $app->testing_tools ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <!-- 監視・ログ -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            監視・ログ
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'error_monitoring' => 'エラー監視',
                'performance_monitoring' => 'パフォーマンス監視',
                'access_logs' => 'アクセスログ',
                'security_logs' => 'セキュリティログ',
                'sentry' => 'Sentry',
                'datadog' => 'Datadog',
                'newrelic' => 'New Relic',
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
    </div>

    <!-- コード品質 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            コード品質管理
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'eslint' => 'ESLint',
                'phpcs' => 'PHP_CodeSniffer',
                'prettier' => 'Prettier',
                'sonarqube' => 'SonarQube',
                'code_review' => 'コードレビュー',
                'static_analysis' => '静的解析',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="code_quality_tools[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('code_quality_tools', $app->code_quality_tools ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <!-- 補足情報 -->
    <div class="mb-6">
        <label for="security_notes" class="block text-sm font-medium text-gray-700">
            セキュリティと品質管理の補足情報
        </label>
        <textarea 
            name="security_notes" 
            id="security_notes"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="セキュリティ対策、パフォーマンス最適化、テスト戦略、監視体制、コード品質管理などについての補足情報があれば入力してください"
        >{{ old('security_notes', $app->security_notes ?? '') }}</textarea>
    </div>
</div> 