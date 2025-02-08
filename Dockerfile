FROM python:3.11

WORKDIR /code

COPY requirements.txt .
RUN pip install -r requirements.txt

COPY . .

# 毎日３時に未使用の画像を削除する機能
# cronをインストール
RUN apt-get update && \
    apt-get install -y cron && \
    rm -rf /var/lib/apt/lists/*

# cronジョブの設定ファイルをコピー
COPY crontab /etc/cron.d/app-cron
RUN chmod 0644 /etc/cron.d/app-cron && \
    crontab /etc/cron.d/app-cron 