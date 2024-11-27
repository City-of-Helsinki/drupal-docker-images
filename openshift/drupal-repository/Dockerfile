FROM alpine:3.20
COPY --from=almir/webhook /usr/local/bin/webhook /usr/local/bin/webhook

# We need php and git to build satis.
RUN apk add --no-cache git php83 php83-openssl php83-common php83-json php83-phar php83-mbstring php83-pecl-apcu php83-zip php83-simplexml make tini

ENV COMPOSER_HOME=/.composer
ENV PROJECT_DIR=/var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/
RUN \
  # COMPOSER_HOME permissions.
  mkdir -p $COMPOSER_HOME && \
  chmod -R a+rwx $COMPOSER_HOME

WORKDIR     /etc/webhook
VOLUME      ["/etc/webhook"]
EXPOSE      9000

# Copy configuration files and scripts
COPY files/ /
RUN chmod +x /entrypoints/* && \
  chmod +x /usr/local/bin/entrypoint
ENTRYPOINT ["/sbin/tini", "--"]

# Default command: Start up multiple services via entrypoint
CMD ["entrypoint"]
