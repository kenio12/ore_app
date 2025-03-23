FROM python:3.11

# 環境変数の設定（ここを追加）
ENV ALLOWED_HOSTS=oreapp-production.up.railway.app,.railway.app,*
ENV DEBUG=False

# Pythonの出力バッファリングを無効化
ENV PYTHONUNBUFFERED=1

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