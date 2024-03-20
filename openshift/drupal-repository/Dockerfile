FROM alpine:3.19
COPY --from=almir/webhook /usr/local/bin/webhook /usr/local/bin/webhook

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
ENTRYPOINT  ["/usr/local/bin/webhook"]
CMD ["-verbose", "-hooks=/etc/webhook/hooks.json", "-template", "-hotreload", "-debug"]
