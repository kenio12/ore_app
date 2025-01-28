<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 1. アプリ基本情報 (01_BasicInfo)
    |--------------------------------------------------------------------------
    */
    'app_types' => [
        'web_app' => 'Webアプリケーション',
        'ios_app' => 'iOSアプリ',
        'android_app' => 'Androidアプリ',
        'windows_app' => 'Windowsアプリ',
        'mac_app' => 'macOSアプリ',
        'linux_app' => 'Linuxアプリ',
        'game' => 'ゲーム',
        'other' => 'その他'
    ],

    'app_status' => [
        'completed' => '完成',
        'in_development' => '開発中'
    ],

    'genres' => [
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
    ],

    /*
    |--------------------------------------------------------------------------
    | 2. 開発期間情報 (02_DevelopmentPeriod)
    |--------------------------------------------------------------------------
    */
    'development_period' => [
        // 新しく追加する開発期間関連の定数
    ],

    /*
    |--------------------------------------------------------------------------
    | 3. ハードウェア環境 (03_Hardware)
    |--------------------------------------------------------------------------
    */
    'hardware' => [
        'device_types' => [
            'windows' => 'Windows PC',
            'mac' => 'Mac',
            'linux' => 'Linux PC',
            'chrome_os' => 'Chrome OS',
            'tablet' => 'タブレット',
            'smartphone' => 'スマートフォン',
            'other' => 'その他'
        ],
        'os_types' => [
            'ios' => 'iOS',
            'android' => 'Android',
            'windows' => 'Windows',
            'macos' => 'macOS',
            'linux' => 'Linux',
            'other' => 'その他'
        ],
        'cpu_types' => [
            'x86' => 'x86/x64',
            'arm' => 'ARM',
            'apple_silicon' => 'Apple Silicon',
            'other' => 'その他'
        ],
        'memory_sizes' => [
            '2gb' => '2GB以下',
            '4gb' => '4GB',
            '8gb' => '8GB',
            '16gb' => '16GB',
            '32gb' => '32GB以上'
        ],
        'storage_types' => [
            'hdd' => 'HDD',
            'ssd' => 'SSD',
            'nvme' => 'NVMe SSD',
            'emmc' => 'eMMC',
            'other' => 'その他'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | 4. 基本開発環境 (04_BasicDevEnvironment)
    |--------------------------------------------------------------------------
    */
    'basic_dev_env' => [
        'team_sizes' => [
            '1' => '1人（個人）',
            '2-5' => '2-5人',
            '6-10' => '6-10人',
            '11-20' => '11-20人',
            '21-50' => '21-50人',
            '50+' => '50人以上'
        ],
        'virtualization_tools' => [
            'docker' => 'Docker',
            'vagrant' => 'Vagrant',
            'virtualbox' => 'VirtualBox',
            'vmware' => 'VMware',
            'wsl' => 'WSL',
            'other' => 'その他'
        ],
        'os_types' => [
            'windows' => 'Windows',
            'macos' => 'macOS',
            'linux' => 'Linux',
            'other' => 'その他'
        ],
        'editors' => [
            'vscode' => 'Visual Studio Code',
            'phpstorm' => 'PhpStorm',
            'sublime' => 'Sublime Text',
            'vim' => 'Vim',
            'atom' => 'Atom',
            'other' => 'その他'
        ],
        'version_control' => [
            'git' => 'Git',
            'github' => 'GitHub',
            'gitlab' => 'GitLab',
            'bitbucket' => 'Bitbucket',
            'svn' => 'SVN',
            'other' => 'その他'
        ],
        'monitor_counts' => [
            '1' => '1台',
            '2' => '2台',
            '3' => '3台',
            '4+' => '4台以上'
        ],
        'monitor_sizes' => [
            '24' => '24インチ以下',
            '27' => '27インチ',
            '32' => '32インチ',
            '34' => '34インチウルトラワイド',
            '38' => '38インチウルトラワイド',
            '43+' => '43インチ以上',
            'other' => 'その他'
        ],
        'monitor_resolutions' => [
            'fhd' => 'FHD (1920x1080)',
            '2k' => '2K (2560x1440)',
            '4k' => '4K (3840x2160)',
            '5k' => '5K以上',
            'other' => 'その他'
        ],
        'communication' => [
            'slack' => 'Slack',
            'discord' => 'Discord',
            'teams' => 'Microsoft Teams',
            'zoom' => 'Zoom',
            'meet' => 'Google Meet',
            'chatwork' => 'Chatwork',
            'skype' => 'Skype',
            'other' => 'その他'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | 5. 開発ツール環境 (05_DevTools)
    |--------------------------------------------------------------------------
    */
    'dev_tools' => [
        'infrastructure' => [
            'aws' => 'AWS',
            'gcp' => 'Google Cloud',
            'azure' => 'Microsoft Azure',
            'heroku' => 'Heroku',
            'vercel' => 'Vercel',
            'digitalocean' => 'DigitalOcean',
            'firebase' => 'Firebase',
            'cloudflare' => 'Cloudflare',
            'other' => 'その他'
        ],
        'ci_cd' => [
            'github_actions' => 'GitHub Actions',
            'gitlab_ci' => 'GitLab CI',
            'jenkins' => 'Jenkins',
            'circle_ci' => 'CircleCI',
            'travis_ci' => 'Travis CI',
            'azure_pipelines' => 'Azure Pipelines',
            'other' => 'その他'
        ],
        'api_tools' => [
            'postman' => 'Postman',
            'swagger' => 'Swagger/OpenAPI',
            'insomnia' => 'Insomnia',
            'curl' => 'cURL',
            'graphql' => 'GraphQL',
            'other' => 'その他'
        ],
        'communication' => [
            'slack' => 'Slack',
            'discord' => 'Discord',
            'teams' => 'Microsoft Teams',
            'zoom' => 'Zoom',
            'meet' => 'Google Meet',
            'chatwork' => 'Chatwork',
            'skype' => 'Skype',
            'other' => 'その他'
        ],
        'monitoring' => [
            'datadog' => 'Datadog',
            'newrelic' => 'New Relic',
            'grafana' => 'Grafana',
            'prometheus' => 'Prometheus',
            'sentry' => 'Sentry',
            'cloudwatch' => 'AWS CloudWatch',
            'stackdriver' => 'Google Cloud Monitoring',
            'other' => 'その他'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | 6. アーキテクチャ (06_ArchitectureSection)
    |--------------------------------------------------------------------------
    */
    'architecture' => [
        'patterns' => [
            'mvc' => 'MVC',
            'mvvm' => 'MVVM',
            'clean' => 'クリーンアーキテクチャ',
            'layered' => 'レイヤードアーキテクチャ',
            'ddd' => 'ドメイン駆動設計',
            'microservices' => 'マイクロサービス',
            'serverless' => 'サーバーレス',
            'other' => 'その他'
        ],
        'design_patterns' => [
            'singleton' => 'Singleton',
            'factory' => 'Factory',
            'observer' => 'Observer',
            'strategy' => 'Strategy',
            'repository' => 'Repository',
            'decorator' => 'Decorator',
            'facade' => 'Facade',
            'other' => 'その他'
        ],
        'hints' => [
            'architecture_structure' => '全体的なアーキテクチャの構成',
            'adoption_reason' => '採用した理由や背景',
            'design_patterns' => '特徴的な設計パターン',
            'module_dependencies' => 'モジュール間の依存関係',
            'data_flow' => 'データフローの概要'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | 7. セキュリティと品質管理 (07_SecuritySection)
    |--------------------------------------------------------------------------
    */
    'security_quality' => [
        'security_measures' => [
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
        ],
        'testing_tools' => [
            'unit_test' => 'ユニットテスト',
            'integration_test' => '統合テスト',
            'e2e_test' => 'E2Eテスト',
            'phpunit' => 'PHPUnit',
            'jest' => 'Jest',
            'cypress' => 'Cypress',
            'coverage' => 'カバレッジ測定',
            'other' => 'その他'
        ],
        'code_quality_tools' => [
            'eslint' => 'ESLint',
            'phpcs' => 'PHP_CodeSniffer',
            'prettier' => 'Prettier',
            'sonarqube' => 'SonarQube',
            'code_review' => 'コードレビュー',
            'static_analysis' => '静的解析',
            'other' => 'その他'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | 8. バックエンド環境 (08_BackendSection)
    |--------------------------------------------------------------------------
    */
    'backend' => [
        'languages' => [
            'php' => 'PHP',
            'python' => 'Python',
            'ruby' => 'Ruby',
            'java' => 'Java',
            'csharp' => 'C#',
            'golang' => 'Go',
            'nodejs' => 'Node.js',
            'other' => 'その他'
        ],
        'frameworks' => [
            'laravel' => 'Laravel',
            'symfony' => 'Symfony',
            'django' => 'Django',
            'flask' => 'Flask',
            'rails' => 'Ruby on Rails',
            'spring' => 'Spring',
            'express' => 'Express',
            'dotnet' => '.NET',
            'other' => 'その他'
        ],
        'package_hints' => [
            'JWT認証',
            'ORM',
            'キャッシュ',
            'メール送信',
            '画像処理'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | 9. フロントエンド環境 (09_FrontendSection)
    |--------------------------------------------------------------------------
    */
    'frontend' => [
        'languages' => [
            'javascript' => 'JavaScript',
            'typescript' => 'TypeScript',
            'html' => 'HTML',
            'css' => 'CSS',
            'other' => 'その他'
        ],
        'frameworks' => [
            'react' => 'React',
            'vue' => 'Vue.js',
            'angular' => 'Angular',
            'svelte' => 'Svelte',
            'next' => 'Next.js',
            'nuxt' => 'Nuxt.js',
            'other' => 'その他'
        ],
        'css_frameworks' => [
            'tailwind' => 'Tailwind CSS',
            'bootstrap' => 'Bootstrap',
            'material' => 'Material UI',
            'chakra' => 'Chakra UI',
            'bulma' => 'Bulma',
            'sass' => 'Sass/SCSS',
            'other' => 'その他'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | 10. データベース環境 (10_DatabaseSection)
    |--------------------------------------------------------------------------
    */
    'database' => [
        'types' => [
            'mysql' => 'MySQL',
            'postgresql' => 'PostgreSQL',
            'mongodb' => 'MongoDB',
            'redis' => 'Redis',
            'sqlite' => 'SQLite',
            'mariadb' => 'MariaDB',
            'oracle' => 'Oracle',
            'mssql' => 'SQL Server',
            'other' => 'その他'
        ],
        'orms' => [
            'eloquent' => 'Eloquent',
            'doctrine' => 'Doctrine',
            'prisma' => 'Prisma',
            'sequelize' => 'Sequelize',
            'typeorm' => 'TypeORM',
            'mongoose' => 'Mongoose',
            'other' => 'その他'
        ],
        'caches' => [
            'redis' => 'Redis',
            'memcached' => 'Memcached',
            'file' => 'ファイルキャッシュ',
            'database' => 'データベースキャッシュ',
            'other' => 'その他'
        ],
        'hosting_services' => [
            'aws_rds' => 'AWS RDS',
            'gcp_sql' => 'Google Cloud SQL',
            'azure_db' => 'Azure Database',
            'heroku_postgres' => 'Heroku Postgres',
            'mongodb_atlas' => 'MongoDB Atlas',
            'planetscale' => 'PlanetScale',
            'supabase' => 'Supabase',
            'other' => 'その他'
        ]
    ],

    'status_options' => [
        'draft' => '非公開',
        'public' => '公開'
    ],

    /*
    |--------------------------------------------------------------------------
    | タブ定義
    |--------------------------------------------------------------------------
    */
    'tabs' => [
        'basic' => [
            'label' => '基本情報',
            'icon' => 'information-circle'
        ],
        'screenshots' => [
            'label' => 'スクリーンショット',
            'icon' => 'photograph'
        ],
        'story' => [
            'label' => '開発ストーリー',
            'icon' => 'book-open'
        ],
        'hardware' => [
            'label' => 'ハードウェア',
            'icon' => 'chip'
        ],
        'dev_env' => [
            'label' => '開発環境',
            'icon' => 'code'
        ],
        'architecture' => [
            'label' => 'アーキテクチャ',
            'icon' => 'template'
        ],
        'frontend' => [
            'label' => 'フロントエンド',
            'icon' => 'desktop-computer'
        ],
        'backend' => [
            'label' => 'バックエンド',
            'icon' => 'server'
        ],
        'database' => [
            'label' => 'データベース',
            'icon' => 'database'
        ],
        'security' => [
            'label' => 'セキュリティ',
            'icon' => 'shield-check'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | アプリケーション共通設定
    |--------------------------------------------------------------------------
    */
    'app_defaults' => [
        'title' => 'ここを書き換えてください',
        'status' => 'draft'
    ]
]; 