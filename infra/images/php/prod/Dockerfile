FROM php:8.4-fpm-alpine AS build

## Installation des utilitaires
RUN apk update && apk add curl git zip

## PHP Extensions & Composer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_pgsql intl zip bcmath && install-php-extensions @composer

## Copie des sources
ARG BUILD_TAG=unknown
RUN echo "BUILD_TAG=${BUILD_TAG}"

ADD build.tar.gz /tmp/sources
RUN mv /tmp/sources/services/optilia-bo/app /var/www/app

WORKDIR /var/www/app

## Installation dépendances
RUN sed -i -e "s;--BUILD_NUMBER--;${BUILD_TAG};g" .env && \
    composer install && \
    php bin/console assets:install && \
    php bin/console asset-map:compile


FROM php:8.4-fpm-alpine AS final

RUN apk update && apk add curl

## Config Timezone
RUN ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime

## PHP Extensions & Composer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_pgsql intl zip bcmath

## Config PHP FPM
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY php.ini "$PHP_INI_DIR/conf.d/prod.ini"
COPY www.conf "/usr/local/etc/php-fpm.d/www.conf"

## Copie des sources
COPY --chown=www-data:www-data --from=build /var/www/app /var/www/app

WORKDIR /var/www/app

VOLUME ["/var/www/app"]
