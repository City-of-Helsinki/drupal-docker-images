FROM alpine:3.23

COPY --from=almir/webhook /usr/local/bin/webhook /usr/local/bin/webhook
EXPOSE 9000

# We need php and git to build satis.
RUN apk add --no-cache git \
  make \
  tini \
  php84 \
  php84-openssl \
  php84-common \
  php84-json \
  php84-phar \
  php84-mbstring \
  php84-pecl-apcu \
  php84-zip \
  php84-simplexml \
  php84-iconv

ENV COMPOSER_HOME=/.composer
ENV PROJECT_DIR=/var/www/html
WORKDIR $PROJECT_DIR
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/
RUN \
  # Make sure composer cache directory is writable.
  mkdir -p $COMPOSER_HOME/cache && \
  chmod -R a+rwx $COMPOSER_HOME

# Copy configuration files and scripts
COPY files /

RUN chmod +x /entrypoints/* && \
  chmod +x /usr/local/bin/entrypoint

ENTRYPOINT ["/sbin/tini", "--"]

# Default command: Start up multiple services via entrypoint
CMD ["entrypoint"]
