FROM alpine:3.22

COPY --from=almir/webhook /usr/local/bin/webhook /usr/local/bin/webhook
EXPOSE 9000

# We need php and git to build satis.
RUN apk add --no-cache git php83 php83-openssl php83-common php83-json php83-phar php83-mbstring php83-pecl-apcu php83-zip php83-simplexml make tini

ENV COMPOSER_HOME=/.composer
ENV PROJECT_DIR=/var/www/html
WORKDIR $PROJECT_DIR
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/
RUN \
  # Make sure composer cache directory is writable.
  mkdir -p $COMPOSER_HOME/cache && \
  chmod -R a+rwx $COMPOSER_HOME

# Copy configuration files and scripts
COPY files/ /

RUN chmod +x /entrypoints/* && \
  chmod +x /usr/local/bin/entrypoint

ENTRYPOINT ["/sbin/tini", "--"]

# Default command: Start up multiple services via entrypoint
CMD ["entrypoint"]
