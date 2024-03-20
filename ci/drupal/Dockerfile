ARG BASE_IMAGE_TAG
FROM php:${BASE_IMAGE_TAG} as base

# Install required tools
RUN apk add --no-cache \
    sqlite \
    sqlite-dev \
    git \
    patch \
    mariadb-client \
    # druidfi/tools has dependency to make and
    # 'make cast-spell' has dependency to bash
    make \
    bash \
    grep \
    jq \
    gnupg \
    coreutils \
    # This is required by MySQL 8 (caching_sha2_password plugin)
    mariadb-connector-c

# install the PHP extensions we need
RUN set -eux; \
	\
	apk add --no-cache --virtual .build-deps \
		coreutils \
		freetype-dev \
		libjpeg-turbo-dev \
		libpng-dev \
		libzip-dev \
    autoconf \
    linux-headers \
    g++ \
    libtool \
	; \
	\
  pecl install apcu pcov; \
  \
	docker-php-ext-configure gd \
		--with-freetype \
		--with-jpeg=/usr/include \
	; \
	\
  docker-php-ext-enable apcu.so pcov.so; \
  \
	docker-php-ext-install -j "$(nproc)" \
    sockets \
		gd \
		opcache \
    bcmath \
		pdo_mysql \
		zip \
    pdo_sqlite \
	; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --virtual .drupal-phpexts-rundeps $runDeps; \
	apk del .build-deps; \
  rm /usr/local/bin/phpdbg \
    /usr/local/bin/php-cgi

# We can't remove files from base layer (php container) so we have to copy everything
# into a new layer to not waste space.
FROM scratch
COPY --from=base / /

# set recommended PHP.ini settings
# see https://secure.php.net/manual/en/opcache.installation.php
RUN { \
		echo 'opcache.memory_consumption=128'; \
		echo 'opcache.interned_strings_buffer=8'; \
		echo 'opcache.max_accelerated_files=4000'; \
		echo 'opcache.revalidate_freq=60'; \
		echo 'opcache.fast_shutdown=1'; \
	} > /usr/local/etc/php/conf.d/opcache-recommended.ini

# override memory limit and upload max filesize and post max size
# to enable bigger uploads
RUN { \
		echo 'memory_limit=512M'; \
		echo 'upload_max_filesize=32M'; \
		echo 'post_max_size=32M'; \
	} > /usr/local/etc/php/conf.d/php-overrides.ini

RUN apk add --no-cache \
  --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community \
  github-cli

# Copy scripts and configuration files.
COPY files/ /

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/

ENV SIMPLETEST_BASE_URL=http://127.0.0.1:8888
ENV SIMPLETEST_DB=mysql://drupal:drupal@db:3306/drupal
ENV COMPOSER_HOME=/tmp/.composer

# Individual module tests use `composer config repositories N $GITHUB_WORKSPACE && composer require drupal/$MODULE_NAME` to
# symlink module's codebase from $GITHUB_WORKSPACE to public/modules/contrib folder. PHPUnit seems to report an incorrect
# test coverage if the test folder is a symlink.
#
# This setting changes composer to mirror the content instead of symlinking it.
# @see https://helsinkisolutionoffice.atlassian.net/browse/UHF-8566
ENV COMPOSER_MIRROR_PATH_REPOS=1

# Automatically discard uncommitted changes. This usually happens when attempting to update a package that
# has any patches applied.
ENV COMPOSER_DISCARD_CHANGES=true

RUN curl https://keybase.io/codecovsecurity/pgp_keys.asc | gpg --no-default-keyring --keyring trustedkeys.gpg --import; \
  curl -Os https://uploader.codecov.io/latest/alpine/codecov; \
  curl -Os https://uploader.codecov.io/latest/alpine/codecov.SHA256SUM; \
  curl -Os https://uploader.codecov.io/latest/alpine/codecov.SHA256SUM.sig; \
  gpgv codecov.SHA256SUM.sig codecov.SHA256SUM; \
  sha256sum -c codecov.SHA256SUM; \
  rm codecov.*; \
  chmod +x codecov; \
  mv codecov /usr/bin/;

USER 1001
