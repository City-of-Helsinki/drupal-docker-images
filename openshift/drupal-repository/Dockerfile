FROM alpine:3.19
COPY --from=almir/webhook /usr/local/bin/webhook /usr/local/bin/webhook

ARG S6_OVERLAY_VERSION=3.1.6.2
ADD https://github.com/just-containers/s6-overlay/releases/download/v${S6_OVERLAY_VERSION}/s6-overlay-noarch.tar.xz /tmp
RUN tar -C / -Jxpf /tmp/s6-overlay-noarch.tar.xz
ADD https://github.com/just-containers/s6-overlay/releases/download/v${S6_OVERLAY_VERSION}/s6-overlay-x86_64.tar.xz /tmp
RUN tar -C / -Jxpf /tmp/s6-overlay-x86_64.tar.xz

# We need php and git to build satis.
RUN apk add --no-cache git php82 php82-openssl php82-common php82-json php82-phar php82-mbstring php82-pecl-apcu php82-zip php82-simplexml make

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
ENTRYPOINT ["/init"]
