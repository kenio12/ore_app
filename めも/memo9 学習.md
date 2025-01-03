# バックエンド
FastApi・・PythonのWebフレームワークなんや！
app/
├── main.py          # メインのアプリケーションファイル
├── models/          # データベースモデル
├── schemas/         # Pydanticスキーマ
├── routes/          # APIエンドポイント
└── dependencies/    # 依存関係

# FastAPIの特徴
1. 高速
   - Pythonフレームワークの中で最速レベル
2. 型ヒントベース
   - Pydanticで自動バリデーション
3. 自動ドキュメント生成
   - Swagger UI (/docs)が自動で使える
4. 非同期処理対応
   - async/awaitが使える
FastAPIは特に：
DjangoよりもAPIに特化してる
コード量が少なくて済む
パフォーマンスが良い
っていう特徴があるんや！

APIでできること：
1. データを取得（GET）
2. データを送信（POST）
3.データを更新（PUT/PATCH）
4. データを削除（DELETE）
つまり：
APIは単なるデータのやり取りの窓口
JSONだけじゃなく、ファイルとかも送れる
フロントとバックでデータをやり取りする手段

例：
今回の開発物語サイトでいうと：
【FastAPI + Next.jsの場合】
ブラウザ（Next.js） ←→ API（FastAPI） ←→ データベース
└→ 見た目の処理      └→ データの処理    └→ データの保存

【Djangoの場合】
ブラウザ ←→ Django ←→ データベース
            └→ URLconf（ルーティング）
            └→ Views（処理）
            └→ Templates（見た目）
            └→ Models（データ）

例えば、データの流れでいうと：
【Django】
1. ブラウザ → Django(Views) 
2. Django(Views) → データベース
3. データベース → Django(Views)
4. Django(Views) → Templates(HTML生成)
5. Templates → ブラウザ

【FastAPI + Next.js】
1. ブラウザ → Next.js
2. Next.js → FastAPI
3. FastAPI → データベース
4. データベース → FastAPI
5. FastAPI → Next.js（JSON）
6. Next.js → ブラウザ（画面描画）



# フロントエンド
Next.js・・・reactのフレームワークらしい
src/
├── app/          # ページのテンプレート
│   ├── layout.tsx    # 全体のレイアウト
│   ├── page.tsx      # トップページ
│   └── globals.css   # 全体のCSS
└── components/   # 再利用可能なコンポーネント

# フロントエンドの技術スタック
Next.js
  └→ React（js + html + cssを書きやすくしたライブラリ）
      ├→ JSX/TSX（HTMLっぽい書き方ができるJS/TS）
      └→ CSS-in-JS（今回はChakra UIってライブラリ使ってる）

# Chakra UIの主な特徴：
1. デザインシステムが組み込み済み
   - 色やサイズが予め設定されてる
   - デザインの一貫性が保ちやすい
2. レスポンシブ対応が簡単
3. ダークモード対応
4. アクセシビリティ対応

# JSXについて
- HTMLっぽい書き方ができるJavaScriptの拡張文法
- 見た目はHTML、実態はJavaScript
- React用の特別な書き方

# DjangoとFastAPI+Nextの違い
Django
- フルスタック（フロント・バック一体型）
- テンプレートエンジンでHTML生成
- 管理画面が標準装備
HTMLテンプレートに縛られる
JavaScriptの機能追加が面倒
画面の更新には基本的にページ全体の再読み込みが必要

FastAPI + Next.js
- バックエンド（FastAPI）とフロント（Next.js）が分離
- APIベースの通信
- より柔軟なフロントエンド開発が可能
- 部分的な画面更新が簡単（ページ全体の再読み込み不要）
アニメーションの追加が簡単
モバイルアプリみたいな動きも実現できる
UIコンポーネントの再利用が簡単
状態管理が柔軟（いいね数とかの動的なデータ）
つまり：
1. より動的なUI作成が可能
ユーザー体験の向上
モダンなWeb機能の実装がしやすい
これが「柔軟性が高い」っていう意味やね！

じゃあジャンゴより、こっちの方がいいじゃん、っていうと次の回答。

Djangoの方が良い場合：
1. 管理画面が必要
Django管理画面が最初から使える
ユーザー管理とか簡単
2. 開発速度重視
1つのフレームワークで全部できる
チーム全員がPythonだけ知ってればOK
3. シンプルなウェブサイト
ブログとか
動的な要素が少ないサイト
FastAPI + Next.jsが良い場合：
1. リッチなUI
SNSみたいな動的な画面
アニメーション多用
スケーラビリティ重視
フロントとバックを別々にスケール可能
APIを他のサービスでも使いまわせる
モバイルアプリ対応
同じAPIをスマホアプリでも使える
つまり：
Djangoは「手早く作りたい」「シンプルに済ませたい」時に◎
FastAPI+Nextは「モダンなUI」「将来の拡張性」重視の時に◎
今回の開発物語サイトは、SNSっぽい動きが多いから、FastAPI+Nextの方が向いてるってことやね！


# 今後の学習ポイント
1. FastAPIでのAPI作成方法
2. Next.jsでのフロントエンド開発
3. フロントとバックの連携方法（API通信）
4. データベース設計と実装