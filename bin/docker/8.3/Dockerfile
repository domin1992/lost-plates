FROM php:8.3-apache

ARG NODE_VERSION=18.19.0
ARG NVM_VERSION=0.39.7
ARG WORKDIR=/var/www/html
ARG HOST_UID=1000

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC
ENV DOCUMENT_ROOT=${WORKDIR}
ENV LARAVEL_PROCS_NUMBER=1
ENV USER=www-data

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV APACHE_CONFDIR /etc/apache2
ENV APACHE_ENVVARS $APACHE_CONFDIR/envvars

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt-get install -y \
        git \
        curl \
        ca-certificates \
        gnupg \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libwebp-dev \
        libmemcached-dev \
        libzip-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        librdkafka-dev \
        libpq-dev \
        libssl-dev \
        openssh-server \
        zip \
        unzip \
        supervisor \
        sqlite3  \
        nano \
        cron \
        libmagickwand-dev \
        gosu \
    && pecl install \
        imagick \
        memcached \
        xdebug \
    && mkdir -p /etc/apt/keyrings \
    && php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && apt-get clean \
    && mkdir -p /usr/local/nvm

ENV NVM_DIR /usr/local/nvm

RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v$NVM_VERSION/install.sh | bash \
    && /bin/bash -c "source $NVM_DIR/nvm.sh && nvm install $NODE_VERSION && nvm use $NODE_VERSION"

ENV NODE_PATH $NVM_DIR/versions/node/v$NODE_VERSION/lib/node_modules
ENV PATH $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp

RUN docker-php-ext-install \
    zip \
    mbstring \
    exif \
    pcntl \
    bcmath \
    -j$(nproc) \
    -j$(nproc) gd \
    intl \
    pdo_mysql

RUN docker-php-ext-enable \
    imagick \
    memcached

WORKDIR $WORKDIR

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY bin/docker/8.3/php.ini $PHP_INI_DIR/conf.d/99-overrides.ini
COPY bin/docker/8.3/supervisord.conf /etc/supervisor/supervisord.conf
COPY bin/docker/8.3/start-container /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container
COPY bin/docker/8.3/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY bin/docker/8.3/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf
COPY bin/docker/8.3/apache2.conf /etc/apache2/apache2.conf
COPY bin/docker/8.3/certificate.crt /etc/ssl/certs/certificate.crt
COPY bin/docker/8.3/certificate.key /etc/ssl/private/certificate.key

RUN chmod 600 /etc/ssl/private/certificate.key \
    && chmod 600 /etc/ssl/certs/certificate.crt \
    && ln -s /etc/apache2/sites-available/default-ssl.conf /etc/apache2/sites-enabled/default-ssl.conf \
    && a2enmod ssl \
    && a2enmod rewrite \
    && a2enmod headers

RUN touch /var/log/cron.log \
    && echo "* * * * * $USER /usr/local/bin/php /var/www/html/artisan schedule:run >> /dev/null 2>&1" >> /etc/crontab

RUN usermod -u ${HOST_UID} $USER \
    && groupmod -g ${HOST_UID} $USER \
    && mkdir /home/$USER \
    && chown -R $USER:$USER /home/$USER \
    && usermod -d /home/$USER $USER \
    && chmod -R 755 $WORKDIR \
    && chown -R $USER:$USER $WORKDIR

EXPOSE 80
EXPOSE 443
EXPOSE 6379

CMD ["start-container"]

HEALTHCHECK CMD curl --fail https://lostplates.test || exit 1