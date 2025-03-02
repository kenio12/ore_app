#!/bin/bash

# PythonAnywhereデプロイスクリプト
# 使用方法: ./deploy_to_pythonanywhere.sh <pythonanywhere_username>

# エラーが発生したらスクリプトを終了
set -e

# ユーザー名をチェック
if [ -z "$1" ]; then
    echo "使用方法: ./deploy_to_pythonanywhere.sh <pythonanywhere_username>"
    exit 1
fi

USERNAME=$1
REMOTE_PATH="/home/$USERNAME/ore_app"
REMOTE_VENV="/home/$USERNAME/.virtualenvs/ore_app"
WSGI_PATH="/var/www/$USERNAME.pythonanywhere.com_wsgi.py"

echo "=== PythonAnywhereへのデプロイを開始します ==="
echo "ユーザー名: $USERNAME"

# 現在のディレクトリがプロジェクトのルートディレクトリであることを確認
if [ ! -f "manage.py" ]; then
    echo "エラー: manage.pyが見つかりません。Djangoプロジェクトのルートディレクトリで実行してください。"
    exit 1
fi

# 静的ファイルを収集
echo "=== 静的ファイルを収集しています ==="
python manage.py collectstatic --noinput

# データベースのマイグレーション
echo "=== データベースのマイグレーションを実行しています ==="
python manage.py migrate

# 全てのPythonファイルが構文的に正しいことを確認
echo "=== Pythonファイルの構文チェックを実行しています ==="
find . -name "*.py" -type f -not -path "./venv/*" -not -path "./env/*" -exec python -m py_compile {} \;

# リモートサーバーに接続して必要なディレクトリを作成
echo "=== リモートディレクトリを準備しています ==="
ssh $USERNAME@ssh.pythonanywhere.com "mkdir -p $REMOTE_PATH"

# ファイルをリモートサーバーにコピー
echo "=== ファイルを転送しています ==="
rsync -avz --exclude "venv" --exclude "env" --exclude "__pycache__" --exclude "*.pyc" --exclude ".git" --exclude "node_modules" . $USERNAME@ssh.pythonanywhere.com:$REMOTE_PATH

# 仮想環境の設定とパッケージのインストール
echo "=== 依存パッケージをインストールしています ==="
ssh $USERNAME@ssh.pythonanywhere.com "
    if [ ! -d $REMOTE_VENV ]; then
        mkvirtualenv -p python3.8 ore_app
    fi
    workon ore_app
    pip install -r $REMOTE_PATH/requirements.txt
"

# WSGIファイルを設定
echo "=== WSGIファイルを設定しています ==="
ssh $USERNAME@ssh.pythonanywhere.com "cat > $WSGI_PATH << 'EOL'
import os
import sys

# プロジェクトのパスを追加
path = '$REMOTE_PATH'
if path not in sys.path:
    sys.path.append(path)

# 仮想環境のパスを追加
VENV_PATH = '$REMOTE_VENV/lib/python3.8/site-packages'
if VENV_PATH not in sys.path:
    sys.path.append(VENV_PATH)

os.environ['DJANGO_SETTINGS_MODULE'] = 'ore_app.settings'

# Djangoのアプリケーションを取得
from django.core.wsgi import get_wsgi_application
application = get_wsgi_application()
EOL"

# WebアプリをリロードしてデプロイをSeifx完了
echo "=== Webアプリをリロードしています ==="
ssh $USERNAME@ssh.pythonanywhere.com "touch $WSGI_PATH"

echo "=== デプロイが完了しました ==="
echo "サイトは https://$USERNAME.pythonanywhere.com/ で確認できます" 