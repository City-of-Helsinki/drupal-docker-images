ARG PHP_SHORT_VERSION=84
ARG TEST_TARGET=php
ARG ALPINE_VERSION=3.22

FROM alpine:${ALPINE_VERSION} AS base

RUN set -eux; \
    apk update && apk upgrade && \
    apk add --no-cache git \
    curl \
    patch \
    rsync \
    bash \
    mariadb-client \
    # This is required by MySQL 8 (caching_sha2_password plugin)
    mariadb-connector-c \
    grep \
    make \
    git \
    tar \
    openssl \
    nginx \
    bash \
    jq \
    tini

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/
COPY --from=amazeeio/envplate:v1.0.3 /usr/local/bin/ep /usr/local/bin/ep

ENV AZURE_SQL_SSL_CA_PATH=/etc/ssl/certs/ca-certificates.crt

# Expose nginx
EXPOSE 8080

# Copy configuration files and scripts
COPY openshift/files/ /
# Copy /etc/profile.d shell scripts to bash equivalent as well.
COPY openshift/files/etc/profile.d/ /etc/bash

RUN chmod +x /entrypoints/* && \
  chmod +x /usr/local/bin/*

ENV PATH=${PATH}:/app/vendor/bin:/var/www/html/vendor/bin
ENV COMPOSER_HOME=/.composer
ENV ENV="/etc/profile"

RUN \
  # Create private files folder
  mkdir -p /private_files && \
  chmod a+rwx /private_files && \
  # Create missing nginx folder.
  mkdir -p /run/nginx && \
  # COMPOSER_HOME permissions.
  mkdir -p $COMPOSER_HOME && \
  chmod -R a+rwx $COMPOSER_HOME && \
  # File permissions to code base
  mkdir -p /var/www/html && \
  chmod -R a+rwx /var/www/html && \
  # Correct permissions to nginx folders.
  chmod -R a+rwx /var/lib/nginx && \
  chmod -R a+rwx /var/log/nginx && \
  chmod -R a+rwx /etc/nginx/conf.d && \
  chmod -R a+rwx /etc/nginx/http.d

ENTRYPOINT ["/sbin/tini", "--"]

# Default command: Start up multiple services via entrypoint
CMD ["entrypoint"]

FROM base AS php83

ENV PHP_VERSION=8.3
RUN set -eux; \
    apk --no-cache add \
    php83-pear \
    php83-fpm \
    php83-curl \
    php83-fileinfo \
    php83-iconv \
    php83-mbstring \
    php83-opcache \
    php83-openssl \
    php83-phar \
    php83-session \
    php83-zip \
    php83-dom \
    php83-pdo \
    php83-pdo_mysql \
    php83-tokenizer \
    php83-xmlreader \
    php83-xmlwriter \
    php83-simplexml \
    php83-sockets \
    php83-ctype \
    php83-gd \
    php83-sodium \
    php83-bcmath \
    php83-pecl-apcu \
    php83-pecl-redis; \
    apk --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community --repository=https://dl-cdn.alpinelinux.org/alpine/edge/main add \
    php83-pecl-imagick; \
    ln -sfn /usr/bin/php83 /usr/bin/php && \
    ln -sfn /usr/sbin/php-fpm83 /usr/bin/php-fpm;

FROM base AS php84

ENV PHP_VERSION=8.4
RUN set -eux; \
    apk --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community --repository=https://dl-cdn.alpinelinux.org/alpine/edge/main add \
    php84-pear \
    php84-fpm \
    php84-curl \
    php84-fileinfo \
    php84-iconv \
    php84-mbstring \
    php84-opcache \
    php84-openssl \
    php84-phar \
    php84-session \
    php84-sockets \
    php84-zip \
    php84-dom \
    php84-pdo \
    php84-pdo_mysql \
    php84-tokenizer \
    php84-xmlreader \
    php84-xmlwriter \
    php84-simplexml \
    php84-ctype \
    php84-gd \
    php84-sodium \
    php84-bcmath \
    php84-pecl-apcu \
    php84-pecl-redis \
    php84-pecl-imagick; \
    ln -sfn /usr/bin/php84 /usr/bin/php && \
    ln -sfn /usr/sbin/php-fpm84 /usr/bin/php-fpm;

FROM php${PHP_SHORT_VERSION} AS final-php
ARG PHP_SHORT_VERSION

ENV TESTSUITE=openshift
# Set recommended PHP.ini settings
# see https://secure.php.net/manual/en/opcache.installation.php
# we set enable_file_override as files won't change while container is up.
RUN { \
  echo 'opcache.memory_consumption=512'; \
  echo 'opcache.interned_strings_buffer=64'; \
  echo 'opcache.max_accelerated_files=30000'; \
  echo 'opcache.enable_file_override=1'; \
  echo 'opcache.validate_timestamps=0'; \
} > /etc/php${PHP_SHORT_VERSION}/conf.d/opcache-recommended.ini

RUN { \
  echo 'memory_limit=512M'; \
  echo 'upload_max_filesize=32M'; \
  echo 'post_max_size=32M'; \
  echo 'max_execution_time=180'; \
  echo 'realpath_cache_size=8M'; \
  echo 'apc.shm_size=64M'; \
} > /etc/php${PHP_SHORT_VERSION}/conf.d/php-overrides.ini

# Override default fpm pool conf to run nginx and php-fpm as same user.
COPY openshift/php-fpm-pool.conf /etc/php${PHP_SHORT_VERSION}/php-fpm.d/www.conf

FROM final-php AS drupal-web-php83
RUN apk --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/edge/testing \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/main  \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/releases add \
    php83-pecl-xdebug \
    php83-pecl-pcov

COPY local/files/etc/php /etc/php83

FROM final-php AS drupal-web-php84
RUN apk --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/edge/testing \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/main  \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community \
    --repository=https://dl-cdn.alpinelinux.org/alpine/edge/releases add \
    php84-pecl-xdebug \
    php84-pecl-pcov

COPY local/files/etc/php /etc/php84

FROM drupal-web-php${PHP_SHORT_VERSION} AS final-drupal-web
ENV DRUPAL_DB_NAME=drupal \
    DRUPAL_DB_USER=drupal \
    DRUPAL_DB_PASS=drupal \
    DRUPAL_DB_HOST=db \
    DRUPAL_DB_PORT=3306

ENV SIMPLETEST_DB="mysql://${DRUPAL_DB_USER}:${DRUPAL_DB_PASS}@${DRUPAL_DB_HOST}:${DRUPAL_DB_PORT}/${DRUPAL_DB_NAME}" \
 SIMPLETEST_BASE_URL=https://app \
 COMPOSER_HOME=/tmp/.composer \
 DRUSH_ALLOW_XDEBUG=1 \
 TESTSUITE=local

RUN apk add --no-cache \
  --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community \
  github-cli \
  gnupg

COPY local/files/ /
RUN chmod +x /entrypoints/* \
    # Drush binary is needed in CI.
    /usr/local/bin/drush

# Remove OPCache optimizations since they hinder development.
RUN rm /etc/php*/conf.d/opcache-recommended.ini

# Generate a self-signed cert for nginx. We use selenium+chromium to run
# Functional javascript tests and it forces all requests to use https now.
# We have a nginx config with a self-signed cert to proxy https requests
# to 8080 port.
# @see files/etc/nginx/http.d/ssl-proxy.conf
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -subj "/C=FI/ST=Uusimaa/L=Helsinki" \
  -keyout /etc/ssl/private/cert.key \
  -out /etc/ssl/certs/cert.crt

RUN chmod a+r /etc/ssl/certs/cert.crt \
    /etc/ssl/private/cert.key

ENV DEFAULT_USER=app \
    DEFAULT_USER_UID=1000

RUN \
    addgroup -S ${DEFAULT_USER} -g ${DEFAULT_USER_UID}; \
    adduser -D -S -G ${DEFAULT_USER} -u ${DEFAULT_USER_UID} -s /bin/bash ${DEFAULT_USER};

WORKDIR /app
USER ${DEFAULT_USER}

FROM final-${TEST_TARGET} AS test

COPY tests/ /tests
USER 0
WORKDIR /tests
# Make sure all envplate substitutions are executed before
# running tests.
RUN printenv && /entrypoints/20-prepare-nginx.sh && \
    composer install && \
    vendor/bin/phpunit --testsuite shared && \
    vendor/bin/phpunit --testsuite $TESTSUITE
