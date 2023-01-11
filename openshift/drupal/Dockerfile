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
    tini \
    nginx \
    bash \
    jq

# Install Composer
COPY --from=composer:2.4 /usr/bin/composer /usr/local/bin/

ENV PATH=${PATH}:/app/vendor/bin
ENV COMPOSER_HOME=/.composer

# Copy configuration files and scripts
COPY files/ /
RUN chmod +x /entrypoints/* && \
  chmod +x /usr/local/bin/entrypoint

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

ENV DRUSH_LAUNCHER_SHA256SUM c6e3e520a33ac7790ab37e5af0fd91fc1108f0830a3a5ebe5b7c36e8782bea30
# Install drush launcher
RUN \
  curl -fSL -o drush "https://github.com/drush-ops/drush-launcher/releases/download/0.10.1/drush.phar" && \
  echo "$DRUSH_LAUNCHER_SHA256SUM *drush" | sha256sum -c -

RUN chmod +x drush && mv drush /usr/local/bin

# Create missing nginx folder.
RUN mkdir -p /run/nginx
COPY drupal.conf /etc/nginx/http.d/default.conf
COPY nginx.conf /etc/nginx/nginx.conf
COPY nginx-custom.locations /etc/nginx/conf.d/custom.locations

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
  chmod -R a+rwx /etc/nginx/conf.d && \
  # Redirect log output to docker log collector.
  ln -sf /proc/1/fd/1 /var/log/nginx/access.log && \
  ln -sf /proc/1/fd/2 /var/log/nginx/error.log

# Expose nginx
EXPOSE 8080

ENTRYPOINT ["/sbin/tini", "--"]

# Default command: Start up multiple services via entrypoint
CMD ["entrypoint"]

# install the PHP extensions we need
FROM php as php8
RUN set -eux; \
  apk add --no-cache --virtual .build-deps \
    php8-dev; \
  apk add --no-cache \
    php8-pear \
    php8-fpm \
    php8-curl \
    php8-fileinfo \
    php8-iconv \
    php8-mbstring \
    php8-opcache \
    php8-openssl \
    php8-phar \
    php8-session \
    php8-zip \
    php8-dom \
    php8-pdo \
    php8-pdo_mysql \
    php8-tokenizer \
    php8-xmlreader \
    php8-xmlwriter \
    php8-simplexml \
    php8-ctype \
    php8-gd \
    php8-pecl-apcu \
    php8-pecl-imagick \
    php8-pecl-redis; \
    pecl8 install mysqlnd_azure; \
    ln -sfn /usr/bin/php8 /usr/bin/php && \
    ln -sfn /usr/sbin/php-fpm8 /usr/bin/php-fpm; \
    apk del .build-deps; \
    echo "extension=mysqlnd_azure.so" >> /etc/php8/conf.d/mysqlnd_azure.ini && \
    echo "mysqlnd_azure.enableRedirect = preferred" >> /etc/php8/conf.d/mysqlnd_azure.ini

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
    php81-pecl-apcu \
    php81-pecl-imagick \
    php81-pecl-redis; \
    ln -sfn /usr/bin/php81 /usr/bin/php && \
    ln -sfn /usr/sbin/php-fpm81 /usr/bin/php-fpm;

FROM php${PHP_SHORT_VERSION} as final
ENV ELASTIC_APM_SHA256SUM 546f5ea32ae77b48a27a3b3794f103a5bcae09637ce9f4b12190e2370d709fbe
RUN set -eux; \
  curl -fSL https://github.com/elastic/apm-agent-php/releases/download/v1.6.2/apm-agent-php_1.6.2_all.apk > apm-agent-php.apk && \
  echo "$ELASTIC_APM_SHA256SUM *apm-agent-php.apk" | sha256sum -c - && \
  apk add --allow-untrusted apm-agent-php.apk && \
  rm apm-agent-php.apk

ENV NEW_RELIC_SHA256SUM 2af08cde4ee75c35e98b1a4276969b4349eb94d8dec24ff547387a92975a8ad7
RUN set -eux; \
  curl https://download.newrelic.com/php_agent/release/newrelic-php5-10.4.0.316-linux-musl.tar.gz > newrelic-php.tar.gz && \
  echo "$NEW_RELIC_SHA256SUM *newrelic-php.tar.gz" | sha256sum -c - && \
  gzip -dc newrelic-php.tar.gz | tar xf - && \
  rm newrelic-php.tar.gz && \
  cd newrelic-php* && \
  NR_INSTALL_SILENT=true NR_INSTALL_USE_CP_NOT_LN=1 ./newrelic-install install && \
  cd .. && \
  rm -rf newrelic-php* && \
  rm -r /tmp/*

ARG PHP_SHORT_VERSION
# Set recommended PHP.ini settings
# see https://secure.php.net/manual/en/opcache.installation.php
RUN { \
		echo 'opcache.memory_consumption=128'; \
		echo 'opcache.interned_strings_buffer=8'; \
		echo 'opcache.max_accelerated_files=4000'; \
		echo 'opcache.revalidate_freq=60'; \
		echo 'opcache.fast_shutdown=1'; \
	} > /etc/php${PHP_SHORT_VERSION}/conf.d/opcache-recommended.ini

# Override memory limit and upload max filesize and post max size
# to enable bigger uploads
RUN { \
		echo 'memory_limit=512M'; \
		echo 'upload_max_filesize=32M'; \
		echo 'post_max_size=32M'; \
    echo 'max_execution_time=180'; \
	} > /etc/php${PHP_SHORT_VERSION}/conf.d/php-overrides.ini

# Newrelic configuration.
RUN { \
  echo 'extension = "newrelic.so"'; \
    echo '[newrelic]'; \
    echo 'newrelic.enabled = true'; \
    echo 'newrelic.license = "${NEW_RELIC_LICENSE_KEY}"'; \
    echo 'newrelic.logfile = "/tmp/php_agent.log"'; \
    echo 'newrelic.appname = "PHP Application"'; \
    echo 'newrelic.daemon.logfile = "/tmp/newrelic-daemon.log"'; \
} > /etc/php${PHP_SHORT_VERSION}/conf.d/newrelic.ini

# Override default fpm pool conf to run nginx and php-fpm as same user.
COPY php-fpm-pool.conf /etc/php${PHP_SHORT_VERSION}/php-fpm.d/www.conf
