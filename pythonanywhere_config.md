# PythonAnywhereでのデプロイ手順

## 1. アカウント作成
まずは [PythonAnywhere](https://www.pythonanywhere.com/) でアカウントを作成します。無料プラン（Beginner）でも問題ありません。

## 2. SSH鍵の設定
デプロイスクリプトを使用するには、SSHキーの設定が必要です。

### ローカルでSSHキーを生成
```bash
ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
```

### 公開鍵をPythonAnywhereに追加
1. PythonAnywhereのダッシュボードにログイン
2. 右上のユーザー名 → Account をクリック
3. 「SSH keys」タブを選択
4. 「Add a new SSH key」セクションに、ローカルの `~/.ssh/id_rsa.pub` の内容をコピーペースト
5. 「Add key」をクリック

## 3. Webアプリの作成
1. PythonAnywhereのダッシュボードで「Web」タブをクリック
2. 「Add a new web app」をクリック
3. ドメイン名はデフォルトのまま（username.pythonanywhere.com）
4. 「Manual configuration」を選択
5. Python 3.8を選択

## 4. データベースの設定
1. PythonAnywhereのダッシュボードで「Databases」タブをクリック
2. MySQLデータベースの初期化（すでに作成されている場合はスキップ）
3. データベース名とパスワードをメモしておく

## 5. 環境変数の設定
Web設定ページの「Environment variables」セクションで以下の環境変数を設定：

```
DATABASE_URL=mysql://username:password@username.mysql.pythonanywhere-services.com/username$default
SECRET_KEY=your_secret_key_here
DEBUG=False
ALLOWED_HOSTS=username.pythonanywhere.com,www.your-custom-domain.com
```

## 6. デプロイの実行
ローカル環境で以下のコマンドを実行してデプロイします：

```bash
chmod +x deploy_to_pythonanywhere.sh
./deploy_to_pythonanywhere.sh your_pythonanywhere_username
```

## 7. 静的ファイルの設定
PythonAnywhereのWeb設定ページで：

1. 「Static files」セクションで以下を追加：
   - URL: `/static/`
   - Directory: `/home/your_username/ore_app/staticfiles/`

2. 「Media files」セクションで以下を追加：
   - URL: `/media/`
   - Directory: `/home/your_username/ore_app/media/`

## 8. Webアプリの再読み込み
Web設定ページの「Reload」ボタンをクリックしてアプリケーションを再読み込みします。

## トラブルシューティング

### エラーログの確認
エラーが発生した場合は、以下のログファイルを確認します：

- エラーログ: `/var/log/your_username.pythonanywhere.com.error.log`
- アクセスログ: `/var/log/your_username.pythonanywhere.com.access.log`
- サーバーログ: PythonAnywhereのWeb設定ページの「Log files」セクション

### 一般的な問題
1. **静的ファイルが表示されない**: collectstaticが正しく実行されているか確認
2. **データベース接続エラー**: 環境変数のデータベースURL設定を確認
3. **500エラー**: エラーログでトレースバックを確認

### 手動デプロイ
スクリプトが動作しない場合は、PythonAnywhereのBashコンソールで以下のコマンドを手動で実行：

```bash
cd ~/ore_app
git pull  # Gitを使用している場合
pip install -r requirements.txt
python manage.py migrate
python manage.py collectstatic --noinput
```

その後、Webページでリロードボタンをクリックします。 