ARG PHP_VERSION
ARG PHP_SHORT_VERSION
ARG ALPINE_VERSION

FROM alpine:${ALPINE_VERSION} as php

RUN echo 'export PS1="[${APP_ENV:-env}] \[\e[1;31m\][${HOSTNAME:-hostname}] \[\e[1;33m\]\w\[\e[0m\] $ "' >> /etc/profile
ENV ENV="/etc/profile"

# Install required tools (https://docs.acquia.com/blt/install/)
# alpine-sdk required for the make script in composer.json
RUN set -eux; \
    apk update && apk upgrade && \
    apk add --no-cache git \
    patch \
    rsync \
    bash \
    mariadb-client \
    alpine-sdk \
    nginx \
    bash \
    jq \
    tini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/

ENV PATH=${PATH}:/app/vendor/bin:/var/www/html/vendor/bin
ENV COMPOSER_HOME=/.composer

# Fetch the required certificate for the Azure MySQL flexible server service
ENV AZURE_SQL_SSL_CA_PATH /usr/local/share/ca-certificates/DigiCertGlobalRootCA.crt.pem
RUN wget https://dl.cacerts.digicert.com/DigiCertGlobalRootCA.crt.pem -O $AZURE_SQL_SSL_CA_PATH

# Fetch the required certificates for the Azure MariaDB service and combine them
ENV AZURE_SQL_SSL_CA_PATH /usr/local/share/ca-certificates/BaltimoreCyberTrustRoot.crt.pem
RUN wget https://www.digicert.com/CACerts/BaltimoreCyberTrustRoot.crt.pem -O /tmp/baltimore.crt.pem && \
  wget https://cacerts.digicert.com/DigiCertGlobalRootG2.crt.pem -O /tmp/g2.crt.pem && \
  cat /tmp/g2.crt.pem >> $AZURE_SQL_SSL_CA_PATH && \
  cat /tmp/baltimore.crt.pem >> $AZURE_SQL_SSL_CA_PATH && \
  rm /tmp/*.crt.pem

# Expose nginx
EXPOSE 8080

# Copy configuration files and scripts
COPY files/ /
RUN chmod +x /entrypoints/* && \
  chmod +x /usr/local/bin/entrypoint

# Create missing nginx folder.
RUN mkdir -p /run/nginx

RUN \
  # COMPOSER_HOME permissions.
  mkdir -p $COMPOSER_HOME && \
  chmod -R a+rwx $COMPOSER_HOME && \
  # File permissions to code base
  mkdir -p /var/www/html && \
  chmod -R a+rwx /var/www/html && \
  # Correct permissions to nginx folders.
  chmod -R a+rwx /var/lib/nginx && \
  chmod -R a+rwx /var/log/nginx && \
  chmod -R a+rwx /etc/nginx/conf.d

ENTRYPOINT ["/sbin/tini", "--"]

# Default command: Start up multiple services via entrypoint
CMD ["entrypoint"]

FROM php as php81
RUN set -eux; \
    apk --no-cache add \
    php81-pear \
    php81-fpm \
    php81-curl \
    php81-fileinfo \
    php81-iconv \
    php81-mbstring \
    php81-opcache \
    php81-openssl \
    php81-phar \
    php81-session \
    php81-zip \
    php81-dom \
    php81-pdo \
    php81-pdo_mysql \
    php81-tokenizer \
    php81-xmlreader \
    php81-xmlwriter \
    php81-simplexml \
    php81-ctype \
    php81-gd \
    php81-sodium \
    php81-pecl-apcu \
    php81-pecl-imagick \
    php81-pecl-redis; \
    ln -sfn /usr/bin/php81 /usr/bin/php && \
    ln -sfn /usr/sbin/php-fpm81 /usr/bin/php-fpm;

FROM php as php82
RUN set -eux; \
    apk --no-cache add \
    php82-pear \
    php82-fpm \
    php82-curl \
    php82-fileinfo \
    php82-iconv \
    php82-mbstring \
    php82-opcache \
    php82-openssl \
    php82-phar \
    php82-session \
    php82-zip \
    php82-dom \
    php82-pdo \
    php82-pdo_mysql \
    php82-tokenizer \
    php82-xmlreader \
    php82-xmlwriter \
    php82-simplexml \
    php82-ctype \
    php82-gd \
    php82-sodium \
    php82-pecl-apcu \
    php82-pecl-redis; \
    apk --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community --repository=https://dl-cdn.alpinelinux.org/alpine/edge/main add \
    php82-pecl-imagick; \
    ln -sfn /usr/bin/php82 /usr/bin/php && \
    ln -sfn /usr/sbin/php-fpm82 /usr/bin/php-fpm;

FROM php as php83
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
    php83-ctype \
    php83-gd \
    php83-sodium \
    php83-pecl-apcu \
    php83-pecl-redis; \
    apk --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community --repository=https://dl-cdn.alpinelinux.org/alpine/edge/main add \
    php83-pecl-imagick; \
    ln -sfn /usr/bin/php83 /usr/bin/php && \
    ln -sfn /usr/sbin/php-fpm83 /usr/bin/php-fpm;

FROM php${PHP_SHORT_VERSION} as final

ARG PHP_SHORT_VERSION

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

# Override memory limit and upload max filesize and post max size
# to enable bigger uploads
RUN { \
  echo 'memory_limit=512M'; \
  echo 'upload_max_filesize=32M'; \
  echo 'post_max_size=32M'; \
  echo 'max_execution_time=180'; \
  echo 'realpath_cache_size=8M'; \
  echo 'apc.shm_size=64M'; \
} > /etc/php${PHP_SHORT_VERSION}/conf.d/php-overrides.ini

# Override default fpm pool conf to run nginx and php-fpm as same user.
COPY php-fpm-pool.conf /etc/php${PHP_SHORT_VERSION}/php-fpm.d/www.conf
