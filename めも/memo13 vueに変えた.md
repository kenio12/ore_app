# 俺のアプリの構成

エラーまみれのNext.jsからVue.jsに変えた。

## データベース
ホスティングサービス名：MongoDB Atlas
種類：MongoDB

## 画像の保存先
ホスティングサービス名：Cloudinary
- 画像の最適化
- CDNによる高速配信
- 簡単な画像加工
- セキュアなストレージ管理

## バックエンド
ホスティングサービス名：Render
フレームワーク：FastAPI
言語：Python
### その他候補
- Render (無料枠あり、使いやすい)
- Railway (無料枠なくなった)
- Heroku (有料)
- Vercel (Nextjs専用)

## フロントエンド
ホスティングサービス名：Render  # 変更：VercelからRenderに
フレームワーク：Vue.js + Vite  # 変更：Next.jsからVue.jsに
言語：TypeScript
### その他候補
- Render (Node.jsアプリのデプロイ可能、無料枠あり)
- Netlify (静的サイト向け、無料枠あり)
- Vercel (Vue.jsもサポート、無料枠あり)

## 開発環境
- Docker Compose による開発環境
- フロントエンド：Vite開発サーバー（ポート5173）
- バックエンド：FastAPI（ポート8000）
- Redis：セッション管理（ポート6379）