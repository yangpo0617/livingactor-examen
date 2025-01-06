FROM php:8.4-fpm-bookworm

ENV DEBIAN_FRONTEND noninteractive

## Installation des utilitaires
RUN apt update && apt upgrade -y && apt install -y curl vim nano postgresql-client git zip

## Config Timezone
RUN rm /etc/localtime && ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime

## PHP Extensions & Composer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_pgsql intl zip bcmath && install-php-extensions @composer

WORKDIR /var/www/app
