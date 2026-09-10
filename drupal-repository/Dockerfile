FROM alpine:3.24

COPY --from=almir/webhook /usr/local/bin/webhook /usr/local/bin/webhook
EXPOSE 9000

# We need php and git to build satis.
RUN apk add --no-cache git \
  make \
  tini \
  php85 \
  php85-openssl \
  php85-common \
  php85-json \
  php85-phar \
  php85-mbstring \
  php85-pecl-apcu \
  php85-zip \
  php85-simplexml \
  php85-iconv

ENV COMPOSER_HOME=/.composer
ENV PROJECT_DIR=/var/www/html
ENV COMPOSER_CACHE_DIR=/tmp

WORKDIR $PROJECT_DIR

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/
RUN \
  # Make sure composer cache directory is writable.
  mkdir -p $COMPOSER_CACHE_DIR/repo/github.com -p && \
  chmod -R a+rwx $COMPOSER_CACHE_DIR && \
  mkdir -p $COMPOSER_HOME && \
  chmod -R a+rwx $COMPOSER_HOME

# Copy configuration files and scripts
COPY files /

RUN chmod +x /entrypoints/* && \
  chmod +x /usr/local/bin/entrypoint

ENTRYPOINT ["/sbin/tini", "--"]

# Default command: Start up multiple services via entrypoint
CMD ["entrypoint"]
