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
    libssl-dev \
    pkg-config \
    patch \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем расширения PHP, необходимые для Symfony
RUN docker-php-ext-install intl zip pdo pdo_mysql iconv mbstring sockets

# and more extensions;
RUN yes '' | pecl install igbinary apcu apcu_bc; \
    docker-php-ext-enable igbinary.so apcu.so; \
    echo "extension=apcu.so" >> /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini; \
    echo "zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20230831/opcache.so" \
        > /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini;

# Создание пользователя с UID=1000
RUN useradd -r -u 1000 -g www-data appuser

RUN mkdir -p /home/appuser/bin

RUN chown appuser:www-data -R /var/www;
RUN chown appuser:www-data -R /home/appuser;

RUN echo 'root:esportsdev' | chpasswd; \
    echo 'appuser:esportsdev' | chpasswd;

RUN echo "appuser ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers

COPY ./config/docker/php-cli/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN mkdir -p /var/log/symfony
RUN chmod a+rwx -R /var/log/symfony

RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*;

USER appuser

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/home/appuser/bin --filename=composer
ENV PATH="/home/appuser/bin:${PATH}"

WORKDIR /var/www/symfony

# The following runs FPM and removes all its extraneous log output on top of what your app outputs to stdout
#CMD php-fpm -F -O 2>&1 | sed -u 's,.*: \"\(.*\)$,\1,'| sed -u 's,\"$,,' 1>&1

CMD ["php-fpm"]