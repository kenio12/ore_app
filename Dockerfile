FROM python:3.11

# 環境変数の設定
ENV PYTHONUNBUFFERED=1
ENV DEBUG=False
ENV ALLOWED_HOSTS=localhost,127.0.0.1,oreapp-production.up.railway.app,.railway.app,*
ENV DJANGO_SETTINGS_MODULE=config.settings

WORKDIR /code

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
        cron && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# cronジョブの設定ファイルをコピー
COPY crontab /etc/cron.d/app-cron
RUN chmod 0644 /etc/cron.d/app-cron && \
    crontab /etc/cron.d/app-cron 