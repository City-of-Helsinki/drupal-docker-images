ARG PHP_VERSION=8.4
ARG PHP_SHORT_VERSION=84
FROM ghcr.io/city-of-helsinki/drupal-docker-base:${PHP_VERSION}-dev AS base

ENV DRUPAL_DB_NAME=drupal \
    DRUPAL_DB_USER=drupal \
    DRUPAL_DB_PASS=drupal \
    DRUPAL_DB_HOST=db \
    DRUPAL_DB_PORT=3306

ENV SIMPLETEST_DB="mysql://${DRUPAL_DB_USER}:${DRUPAL_DB_PASS}@${DRUPAL_DB_HOST}:${DRUPAL_DB_PORT}/${DRUPAL_DB_NAME}"

COPY --from=ghcr.io/city-of-helsinki/drupal-oc-cli:latest /usr/bin/oc /usr/bin/oc

COPY entrypoints/ /entrypoints
RUN chmod +x /entrypoints/*

COPY files/etc/nginx /etc/nginx

# The old druidfi/drupal-web image used /app folder. Symlink it to
# keep this compatible with it.
RUN ln -s /var/www/html /app

ARG PHP_SHORT_VERSION
# Remove opcache-recommended.ini because we make several performance
# changes to OPcache settings that may hinder local development.
RUN rm /etc/php${PHP_SHORT_VERSION}/conf.d/opcache-recommended.ini

# Generate a self-signed cert for nginx. We use selenium+chromium to run
# Functional javascript tests and it forces all requests to use https now.
# We have a nginx config with a self-signed cert to proxy https requests
# to 8080 port.
# @see files/etc/nginx/http.d/ssl-proxy.conf
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -subj "/C=FI/ST=Uusimaa/L=Helsinki" \
  -keyout /etc/ssl/private/cert.key \
  -out /etc/ssl/certs/cert.crt

FROM base AS php84
RUN apk --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/edge/testing \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/main  \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/releases add php84-pecl-xdebug php84-pecl-pcov

COPY files/etc/php /etc/php84
