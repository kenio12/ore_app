FROM python:3.11

# 環境変数の設定
ENV PYTHONUNBUFFERED=1
ENV DEBUG=False
ENV ALLOWED_HOSTS=localhost,127.0.0.1,*.onrender.com,render.com,*
ENV DJANGO_SETTINGS_MODULE=config.settings
ENV PORT=8080

WORKDIR /app

# まずgunicornと必要なパッケージを確実にインストール
RUN pip install gunicorn==21.2.0 dj-database-url==2.1.0 whitenoise==6.6.0

# 残りのパッケージをインストール 
COPY requirements.txt .
RUN pip install -r requirements.txt

COPY . .

# cronのインストール（Debian Bookworm用）
RUN set -ex && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    mkdir -p /etc/apt/keyrings && \
    apt-get update --allow-insecure-repositories && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y --allow-unauthenticated \
        ca-certificates \
        cron \
        curl && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# cronジョブの設定ファイルをコピー
COPY crontab /etc/cron.d/app-cron
RUN chmod 0644 /etc/cron.d/app-cron && \
    crontab /etc/cron.d/app-cron 

# 静的ファイルを収集
RUN python manage.py collectstatic --noinput

# マイグレーションを実行
RUN python manage.py migrate

# デフォルトコマンドとしてgunicornを使用（Render用）
CMD gunicorn config.wsgi:application --bind 0.0.0.0:$PORT --workers 4 --timeout 120 