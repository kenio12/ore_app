FROM php:8.2-fpm

# 必要なパッケージのインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libicu-dev \
    libzip-dev

# Node.jsとnpmの最新版をインストール
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# PHP拡張のインストール
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# PHPの設定ファイルをコピー（パスを修正）
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

WORKDIR /var/www/html

# ユーザーを作成
RUN useradd -G www-data,root -u 1000 -d /home/dev dev
RUN mkdir -p /home/dev/.composer && \
    chown -R dev:dev /home/dev

# 権限の設定
RUN chown -R dev:dev /var/www/html 