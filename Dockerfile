FROM python:3.11

# 環境変数の設定
ENV PYTHONUNBUFFERED=1
ENV DEBUG=False
ENV ALLOWED_HOSTS=localhost,127.0.0.1,oreapp-production.up.railway.app,.railway.app,*
ENV DJANGO_SETTINGS_MODULE=config.settings
ENV PORT=8080

WORKDIR /code

# まずgunicornと必要なパッケージを確実にインストール
RUN pip install gunicorn==21.2.0 whitenoise==6.4.0 dj-database-url==2.1.0

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

# gunicornのインストールを確認
RUN which gunicorn || echo "gunicorn not found"

# デフォルトコマンドとしてgunicornを使用（Railway用）
CMD python -m gunicorn config.wsgi:application --bind 0.0.0.0:$PORT --log-level debug 