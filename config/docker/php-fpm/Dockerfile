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

# Создаем пользователя www-data и устанавливаем его UID и GID
#RUN groupadd -g 1000 www-data && useradd -u 1000 -g www-data -s /bin/bash -m www-data
#RUN chsh www-data -s /bin/bash
#RUN mkdir -p /home/www-data/bin
#RUN chown www-data:www-data -R /var/www;
#RUN chown www-data:www-data -R /home/www-data;

#RUN groupadd -r appuser -g 1000 && useradd -r -u 1000 -g appuser appuser

# Создание пользователя с UID=1000
RUN useradd -r -u 1000 -g www-data appuser

RUN mkdir -p /home/appuser/bin

RUN chown appuser:www-data -R /var/www;
RUN chown appuser:www-data -R /home/appuser;

RUN echo 'root:esportsdev' | chpasswd; \
    echo 'appuser:esportsdev' | chpasswd;

RUN echo "appuser ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers

COPY ./config/docker/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN mkdir -p /var/log/symfony
RUN chmod a+rwx -R /var/log/symfony

RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*;

USER appuser

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/home/appuser/bin --filename=composer
ENV PATH="/home/appuser/bin:${PATH}"

WORKDIR /var/www/symfony

EXPOSE 9000

# The following runs FPM and removes all its extraneous log output on top of what your app outputs to stdout
#CMD php-fpm -F -O 2>&1 | sed -u 's,.*: \"\(.*\)$,\1,'| sed -u 's,\"$,,' 1>&1

CMD ["php-fpm"]