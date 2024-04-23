ARG PHP_VERSION
ARG PHP_SHORT_VERSION
FROM druidfi/drupal-web:php-${PHP_VERSION} as base

COPY --from=ghcr.io/city-of-helsinki/drupal-oc-cli:latest /usr/bin/oc /usr/bin/oc

RUN sudo apk add --no-cache jq \
  # This is required by mysql 8 (caching_sha2_password)
  mariadb-connector-c

# Autostart drush server and syslog services.
COPY entrypoints/ /entrypoints

COPY files/ /

# Override default fpm pool conf to run nginx and php-fpm as same user.
COPY php-fpm-pool.conf /etc/php${PHP_SHORT_VERSION}/php-fpm.d/www.conf

RUN grep -qxF 'xdebug.log' /etc/php${PHP_SHORT_VERSION}/conf.d/xdebug.ini || echo 'xdebug.log=/tmp/xdebug.log' | sudo tee -a /etc/php${PHP_SHORT_VERSION}/conf.d/xdebug.ini && \
  sudo -u root touch /tmp/xdebug.log && \
  sudo chmod 666 /tmp/xdebug.log

# Add druid user to nginx group to prevent permission issues with drush and 'asset://'
# files.
RUN sudo addgroup druid nginx

ENV SIMPLETEST_DB="mysql://${DRUPAL_DB_USER}:${DRUPAL_DB_PASS}@${DRUPAL_DB_HOST}:${DRUPAL_DB_PORT}/${DRUPAL_DB_NAME}"

FROM base as php81
RUN sudo apk --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/edge/testing  \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/main  \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community add php81-pecl-pcov php81-pecl-imagick

FROM base as php82
RUN sudo apk --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/edge/testing \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/main  \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/releases add php82-pecl-imagick php82-pecl-pcov

FROM base as php83
RUN sudo apk --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/edge/testing \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/main  \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/releases add php83-pecl-imagick php83-pecl-pcov
