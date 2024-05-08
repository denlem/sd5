FROM php:8.3-fpm

RUN apt-get update;  \
    apt-get -y upgrade; \
    apt-get install -y --no-install-recommends apt-utils apt-transport-https; \
    apt-get install -y \
    curl \
    wget sudo\
    git \
    unzip \
    zlib1g-dev \
    libmcrypt-dev \
    libicu-dev \
    libzip-dev \
    libssl-dev pkg-config patch libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем расширения PHP, необходимые для Symfony
RUN docker-php-ext-install intl zip pdo pdo_mysql iconv mbstring sockets

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Создаем пользователя www-data и устанавливаем его UID и GID
# RUN groupadd -g 1000 www-data && useradd -u 1000 -g www-data -s /bin/bash -m www-data

# change /var/www owner to www-data
RUN chown www-data:www-data -R /var/www;

# Переключаемся на пользователя www-data
USER www-data

WORKDIR /var/www/symfony