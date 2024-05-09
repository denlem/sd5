FROM php:8.3-fpm

RUN apt-get update;  \
    apt-get -y upgrade; \
    apt-get install -y --no-install-recommends apt-utils apt-transport-https; \
    apt-get install -y \
    curl \
    gnupg \
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

# Создаем пользователя www-data и устанавливаем его UID и GID
#RUN groupadd -g 1000 www-data && useradd -u 1000 -g www-data -s /bin/bash -m www-data
#RUN chsh www-data -s /bin/bash
#RUN mkdir -p /home/www-data/bin
#RUN chown www-data:www-data -R /var/www;
#RUN chown www-data:www-data -R /home/www-data;

# Создание пользователя с UID=1000
RUN groupadd -r appuser -g 1000 && useradd -r -u 1000 -g appuser appuser

RUN mkdir -p /home/appuser/bin

RUN chown appuser:appuser -R /var/www;
RUN chown appuser:appuser -R /home/appuser;

USER appuser

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/home/appuser/bin --filename=composer
ENV PATH="/home/appuser/bin:${PATH}"

# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Переключаемся на пользователя www-data
# USER www-data

WORKDIR /var/www/symfony