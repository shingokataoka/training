FROM php:8.0-fpm

ENV TZ Asia/Tokyo

RUN apt update && \
	apt install -y git vim unzip libzip-dev libicu-dev libonig-dev && \
	docker-php-ext-install intl pdo_mysql zip bcmath

RUN apt install -y npm nodejs

# GDライブラリをインストール
# コメントアウトのは上記でインストール済のやつ
RUN apt-get update \
    && apt-get install -y \
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

COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

WORKDIR /app
