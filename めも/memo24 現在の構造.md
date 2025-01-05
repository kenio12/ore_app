ore_app/                    # プロジェクトルート
├── docker/                 【必須】
│   ├── backend/           # PHP実行環境
│   │   ├── Dockerfile    【必須】PHPコンテナの設定
│   │   │   - PHP 8.2ベースイメージ
│   │   │   - 必要な拡張機能
│   │   │   - 実行環境の設定
│   │   │
│   │   └── php.ini       【必須】PHP設定
│   │       - メモリ制限
│   │       - タイムゾーン
│   │       - エラー表示
│   │
│   └── nginx/             # Webサーバー
│       ├── Dockerfile    【必須】Nginxコンテナの設定
│       │   - Nginxベースイメージ
│       │   - サーバー設定
│       │
│       └── default.conf  【必須】Nginx設定
│           - ポート設定
│           - PHPとの連携
│           - 静的ファイル
│
├── docker-compose.yml      【必須】Docker構成
│   - コンテナ定義
│   - ネットワーク設定
│   - ボリューム設定
│
├── src/                    【必須】Laravelプロジェクト
│   ├── app/               【必須】アプリケーションコード
│   │   ├── Console/      - Artisanコマンド
│   │   ├── Exceptions/   - エラーハンドリング
│   │   ├── Http/         - ミドルウェア、コントローラー
│   │   ├── Models/       - Eloquentモデル
│   │   ├── Modules/      - モジュラーモノリス構造
│   │   │   └── AppPost/  - アプリ投稿モジュール
│   │   │       ├── Models/
│   │   │       │   └── AppPost.php
│   │   │       └── Views/
│   │   │           └── home.blade.php
│   │   │
│   │   ├── Providers/    - サービスプロバイダー
│   │   └── View/         - ビューコンポーネント
│   │
│   ├── bootstrap/         【必須】起動処理
│   │   ├── app.php       - アプリケーション起動
│   │   └── cache/        - 設定キャッシュ
│   │
│   ├── config/           【必須】設定ファイル
│   │   - アプリケーション設定
│   │   - データベース設定
│   │   - キャッシュ設定
│   │
│   ├── database/         【必須】データベース関連
│   │   ├── migrations/   - テーブル定義
│   │   └── seeders/     - 初期データ
│   │
│   ├── public/           【必須】公開ディレクトリ
│   │   - index.php
│   │   - アセットファイル
│   │
│   ├── resources/        【必須】リソース
│   │   ├── css/         - スタイルシート
│   │   ├── js/          - JavaScript
│   │   └── views/       - ビューテンプレート
│   │
│   ├── routes/           【必須】ルーティング
│   │   - web.php
│   │   - api.php
│   │
│   ├── storage/          【必須】ストレージ
│   │   ├── app/         - ファイルストレージ
│   │   ├── framework/   - フレームワーク用
│   │   └── logs/        - ログファイル
│   │
│   └── vendor/           【必須】Composerパッケージ
│       └── laravel/
│           ├── framework/ 【必須】フレームワークコア
│           ├── pail/     【任意】デバッグツール
│           ├── pint/     【任意】コードフォーマッター
│           └── prompts/  【必須】CLIツール
│
└── めも/                   # プロジェクト設計書
    ├── memo23 設計し直し.md
    └── memo15 アプリの詳細画面について.md


だが、次に修正予定

app/
└── Modules/
    ├── Home/          # エントリーポイント
    │   └── Views/
    │       └── home.blade.php
    │
    ├── Common/        # 共通機能
    │   └── Views/
    │       └── layouts/
    │           └── app.blade.php
    │
    └── AppPost/       # アプリ投稿機能
        └── Models/
            └── AppPost.php

