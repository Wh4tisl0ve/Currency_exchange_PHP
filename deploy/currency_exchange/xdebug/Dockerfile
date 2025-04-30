FROM composer:2.2 AS composer

FROM php:8.3-fpm AS php-extensions
ENV TZ=Europe/Moscow
RUN apt-get -qq update && apt-get -qq --yes install libfcgi-bin
RUN apt-get -qq update && apt-get -qq --yes install libpq-dev \
    && docker-php-ext-install -j$(nproc) pgsql pdo_pgsql

RUN apt-get -qq update && apt-get -qq --yes install git unzip
RUN pecl channel-update pecl.php.net && pecl install xdebug && docker-php-ext-enable xdebug
RUN apt-get -qq update && apt-get -qq --yes install procps

COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /www

FROM php-extensions AS composer-install
COPY --chown=$UID:$GID ./composer.json ./composer.lock /www/
RUN --mount=type=cache,target=/var/www/.composer/cache,uid=$UID,gid=$GID composer install --no-interaction --optimize-autoloader
COPY --chown=$UID:$GID ./ /www/
