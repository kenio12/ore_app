アプリを投稿
基本情報
アプリ名 *
このサイト内の公開状態 *

選択してください
アプリへのアクセス
https://your-demo-site.com
GitHubリポジトリURL
https://github.com/username/repo
アプリの状態 *

選択してください
開発期間 *
0
年
0
ヶ月
ジャンル（複数選択可） *

ゲーム

コミュニケーション

SNS

ネットショップ

マッチングサービス

学習サービス

仕事

娯楽

日常生活

ヘルスケア

金融

ニュース・メディア

飲食・フード

旅行・観光

不動産

教育

採用・求人

文学

美術

音楽

ペット

スポーツ

学問

広告・宣伝

イベント

その他
アプリの種類（複数選択可） *

タイプ未設定

Webアプリ

iPhoneアプリ

Androidアプリ

Windowsアプリ

Macアプリ

Linuxアプリ

CLIツール

ゲーム

その他
スクリーンショット（3枚まで）
選択されていません
※ 3枚まで、1枚あたり5MB以下

ドラッグ&ドロップでもアップロード可能です

このアプリの説明 *
ハードウェア環境
開発体制
個人開発
少人数チーム（2-5人）
中規模チーム（6-15人）
大規模チーム（16人以上）
その他
PC/OS
MacBook
iMac
Mac mini
Mac Pro
Windows PC
Linux PC
その他
周辺機器
マルチディスプレイ
メカニカルキーボード
トラックパッド
外付けマウス
その他
選定理由・工夫した点
なぜこのハードウェア環境を選んだ？どんな工夫をした？
ソフトウェア環境
バージョン管理
Git
GitHub
GitLab
Bitbucket
その他
エディタ/IDE
VSCode
IntelliJ IDEA
WebStorm
PyCharm
Vim
Sublime Text
Cursor
Xcode
Android Studio
Eclipse
Neovim
Emacs
その他
AI/開発支援ツール
GitHub Copilot
ChatGPT
Claude
その他
開発環境/インフラ
Docker
Docker Compose
Kubernetes
Vagrant
WSL
VirtualBox
VMware
AWS Cloud9
GitHub Codespaces
GitPod
その他
選定理由・工夫した点
なぜこのソフトウェア環境を選んだ？どんな工夫をした？
バックエンド環境
言語
Python
Node.js
Go
Java
Ruby
PHP
Rust
その他
フレームワーク
FastAPI
Django
Flask
Express
NestJS
Spring Boot
Laravel
Ruby on Rails
その他
ORM/ODM
Prisma
TypeORM
Sequelize
SQLAlchemy
Mongoose
その他
ホスティングサービス
Render
Heroku
Railway
Fly.io
Deta Space
AWS EC2
AWS Lambda
AWS Elastic Beanstalk
Google App Engine
Google Cloud Run
Azure App Service
その他
デプロイ環境・CI/CD
Git Push Deploy
GitHub Actions
GitLab CI
CircleCI
Jenkins
AWS CodePipeline
Google Cloud Build
Azure Pipelines
Heroku CI/CD
Render Auto-Deploy
その他
選定理由・工夫した点
なぜこの技術スタックを選んだ？どんな工夫をした？
フロントエンド環境
言語
TypeScript
JavaScript
その他
フレームワーク・ライブラリ
React
Vue.js
Next.js
Nuxt
Angular
Svelte
SvelteKit
Astro
その他
UIライブラリ
TailwindCSS
Material-UI
Chakra UI
Vuetify
Ant Design
Bootstrap
Shadcn UI
その他
状態管理
Redux
Vuex
Pinia
Recoil
Zustand
Jotai
その他
その他のツール
Vite
Webpack
ESLint
Prettier
Storybook
Vitest
Jest
その他
選定理由・工夫した点
なぜこの技術スタックを選んだ？どんな工夫をした？
データベース・ストレージ周り
データベース種類
PostgreSQL
MySQL
MongoDB
Redis
その他
データベースホスティング
MongoDB Atlas
AWS RDS
Supabase
Firebase
PlanetScale
その他
ストレージサービス
AWS S3
Cloudinary
Firebase Storage
Supabase Storage
その他
選定理由・工夫した点
なぜこれらのデータベース・ストレージを選んだ？どんな工夫をした？
その他
認証
JWT
OAuth
Firebase Auth
Auth0
Supabase Auth
その他
その他のツール・サービス
CI/CD
監視ツール
その他
選定理由・工夫した点
なぜこれらのツール・サービスを選んだ？どんな工夫をした？
開発ストーリー
開発動機（任意）
なぜこのアプリを作ろうと思った？どんな課題を解決したかった？
苦労した点・課題（任意）
開発中に直面した課題は？どのように解決した？
工夫した点（任意）
アプリ開発でどんな工夫をした？特にこだわったポイントは？
学んだこと（任意）
この開発を通じて何を学んだ？次に活かせることは？
今後の展望（任意）
今後どのような機能を追加したい？どう発展させていきたい？
開発期間（任意）

忘れそうなので、これ追加

// アーキテクチャパターン選択
<div class="form-group">
    <label>アーキテクチャパターン</label>
    <div class="checkbox-group">
        <label>
            <input type="checkbox" name="architecture[]" value="mvc">
            従来のMVC
        </label>
        <label>
            <input type="checkbox" name="architecture[]" value="modular">
            モジュラーモノリス
        </label>
        <label>
            <input type="checkbox" name="architecture[]" value="ddd">
            ドメイン駆動設計（DDD）
        </label>
        <label>
            <input type="checkbox" name="architecture[]" value="clean">
            クリーンアーキテクチャ
        </label>
        <label>
            <input type="checkbox" name="architecture[]" value="onion">
            オニオンアーキテクチャ
        </label>
    </div>
    
    <!-- 選定理由 -->
    <textarea name="architecture_reason" 
              placeholder="なぜこのアーキテクチャを選んだ？どんな工夫をした？">
    </textarea>
</div>