ORE_APP 設計まとめ

0.このアプリについて
memo13
memo14

を参照

1. アプリケーション概要
- プログラマー向けポートフォリオ共有サイト
- 個展形式の表示
- 新米プログラマーの支援

2. アーキテクチャ選択
- Laravel + Blade
- モジュラーモノリス
ore_app/                    # プロジェクトルート
├── docker-compose.yml      # Docker設定
├── docker/                 # Dockerファイル
│   ├── backend/
│   │   └── Dockerfile
│   └── nginx/
│       └── default.conf
│
├── めも/                   # 備忘録（既存）
│   └── memo21 方針.md
│
├── src/                    # Laravelプロジェクト
│   ├── app/
│   │   ├── Modules/       # モジュラーモノリス構造
│   │   └── Shared/       # 共有コンポーネント
│   │
│   ├── database/
│   └── resources/
│
└── .gitignore             # Git除外設定

src/
└── app/
    ├── Modules/              
    │   ├── App/         # アプリモジュール
    │   │   ├── Controllers/
    │   │   ├── Models/
    │   │   └── Views/
    │   │       ├── Forms/      # 8セクションのフォーム
    │   │       ├── Cards/      # カード表示（ホームページ用）
    │   │       └── Details/    # 詳細表示
    │   │
    │   ├── Profile/         # プロフィールモジュール
    │   │   ├── Controllers/
    │   │   ├── Models/
    │   │   └── Views/
    │   │       ├── Show/       # プロフィール表示
    │   │       └── Edit/       # プロフィール編集
    │   │
    │   ├── DevBlog/         # 開発ブログモジュール
    │   │   ├── Controllers/
    │   │   ├── Models/
    │   │   └── Views/
    │   │       ├── List/       # ブログ一覧
    │   │       ├── Show/       # ブログ詳細
    │   │       └── Create/     # ブログ作成
    │   │
    │   ├── Comment/         # コメントモジュール
    │   │   ├── Controllers/
    │   │   ├── Models/
    │   │   └── Views/
    │   │       ├── List/       # コメント一覧
    │   │       └── Form/       # コメントフォーム
    │   │
    │   ├── Inquiry/         # 問い合わせモジュール
    │   │   ├── Controllers/
    │   │   ├── Models/
    │   │   └── Views/
    │   │       ├── Form/       # 問い合わせフォーム
    │   │       └── List/       # 問い合わせ一覧
    │   │
    │   ├── Search/          # 検索モジュール
    │   │   ├── Controllers/
    │   │   ├── Models/
    │   │   └── Views/
    │   │       └── Results/    # 検索結果表示
    │   │
    │   ├── Home/            # ホームページモジュール
    │   │   ├── Controllers/
    │   │   └── Views/
    │   │       └── Cards/      # カード一覧表示
    │   │
    │   └── Auth/            # 認証モジュール（Laravel Breeze）
    │       ├── Controllers/
    │       └── Views/
    │           ├── Login/
    │           ├── Register/
    │           └── Password/
    │
    └── Shared/              # 共有コンポーネント
        ├── Media/           # 画像管理（Cloudinary）
        ├── UI/              # 共通UI
        └── Services/        # 共通サービス


3. 技術スタック
- バックエンド：Laravel
- フロントエンド：Blade + TailwindCSS
- データベース：MySQL
- 画像管理：Cloudinary
- 認証：Laravel Breeze
- デプロイ：Render

4. データベース構成
- users（ユーザー/プロフィール）
- app_posts（アプリ投稿）
- dev_blogs（開発ブログ）
- comments（コメント）
- inquiries（問い合わせ）

5. 主要機能
- ホーム（アプリ一覧、検索）
- アプリ投稿（8セクション）
- 開発ブログ
- プロフィール（自分のアプリ一覧も含む）
- コメント
- 問い合わせ
- 認証
- 共通のナビゲーションバー

6. 開発環境
- Docker
- Git

7. コンポーネントの順番変更
次の順番でVueからLaravel Bladeに作り直す
1. 01_BasicInfoForm.vue（基本情報）
08_DevelopmentStoryForm.vue（開発ストーリー）
02_HardwareSection.vue（ハードウェア環境）
3. 03_SoftwareEnvironmentSection.vue（ソフトウェア環境）
09_ArchitectureSection.vue（アーキテクチャパターン）
4. 04_BackendSection.vue（バックエンド環境）
05_FrontendSection.vue（フロントエンド環境）
06_DatabaseSection.vue（データベース・ストレージ）
07_OthersSection.vue（その他）

