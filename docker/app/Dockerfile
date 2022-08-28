FROM php:fpm
# FROM php:8.0-fpm

ENV TZ Asia/Tokyo

RUN apt update && \
	apt install -y git vim unzip libzip-dev libicu-dev libonig-dev && \
	docker-php-ext-install intl pdo_mysql zip bcmath

# npmとnode.jsをインストール
RUN apt install -y npm nodejs

# node.jsを推奨版にアップデート（nを使う方法）
# まずnをインストール
RUN npm install n -g
# node.js推奨版をインストール
RUN n stable
# 古いnode.jsを削除
RUN apt purge -y nodejs npm
RUN exec /bin/bash -l

# npmをアップデートする
RUN npm update -g npm

# GDライブラリをインストール
# コメントアウトのは上記でインストール済のやつ
RUN apt update \
    && apt install -y \
                # git \
                # zip \
                # unzip \
                # libonig-dev \
                libfreetype6-dev \ 
                libjpeg62-turbo-dev \
                libpng-dev \
                libjpeg-dev \
    && docker-php-ext-install \ 
                # pdo_mysql \ 
                # bcmath \
                gd \
    && docker-php-ext-configure gd \
                --with-freetype=/usr/include/ \
                --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

COPY ./docker/app/php.ini /usr/local/etc/php/php.ini

# docker公式のcomposerイメージをマルチステージビルド（必要な部分だけコピーして残りは破棄される）でインストール
COPY --from=composer /usr/bin/composer /usr/bin/composer
# バージョン指定でcompsoerをインストールする場合は以下
# COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

WORKDIR /app
