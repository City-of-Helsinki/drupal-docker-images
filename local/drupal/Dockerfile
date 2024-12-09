ARG PHP_VERSION
ARG PHP_SHORT_VERSION
FROM druidfi/drupal-web:php-${PHP_VERSION} AS base

COPY --from=ghcr.io/city-of-helsinki/drupal-oc-cli:latest /usr/bin/oc /usr/bin/oc

RUN sudo apk add --no-cache jq openssl

RUN sudo composer self-update

COPY entrypoints/ /entrypoints
COPY files/ /

# Override default fpm pool conf to run nginx and php-fpm as same user.
COPY php-fpm-pool.conf /etc/php${PHP_SHORT_VERSION}/php-fpm.d/www.conf

# Add druid user to nginx group to prevent permission issues with drush and 'asset://'
# files.
RUN sudo addgroup druid nginx

RUN sudo -u root touch /tmp/xdebug.log && \
    sudo chmod 666 /tmp/xdebug.log

RUN sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -subj "/C=FI/ST=Uusimaa/L=Helsinki" \
  -keyout /etc/ssl/private/cert.key \
  -out /etc/ssl/certs/cert.crt

ENV SIMPLETEST_DB="mysql://${DRUPAL_DB_USER}:${DRUPAL_DB_PASS}@${DRUPAL_DB_HOST}:${DRUPAL_DB_PORT}/${DRUPAL_DB_NAME}"

FROM base AS php83
RUN sudo apk --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/edge/testing \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/main  \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/releases add php83-pecl-imagick php83-pecl-pcov

FROM base AS php84
RUN sudo apk --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/edge/testing \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/main  \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/releases add php84-pecl-imagick php84-pecl-pcov
