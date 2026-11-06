# Images for City-of-Helsinki/helfi-search. The project builds its OpenShift
# images on the latest tags (docker/openshift/*/Dockerfile) and its local images
# on the dev tags (docker/local/Dockerfile).
#
# Both run as any non-root UID, as OpenShift's restricted SCC requires (a
# random UID in group 0), with no capabilities. PHP 8.5.
#
# The upstream images aren't pinned: every build uses the latest FrankenPHP
# (with PHP 8.5) and the latest Elastic Open Crawler. Changes are tested
# through the dev tags before they're released as latest.


# helfi-search-webhook: FrankenPHP (PHP inside the Caddy web server). Every
# request goes to the front controller, /app/public/index.php.
FROM dunglas/frankenphp:php8.5-alpine AS webhook

# The image has no php.ini, and PHP's built-in defaults display errors to the
# client, don't log them, and answer a fatal error with HTTP 200. Use PHP's
# production settings: errors go to the log, and a fatal error gives HTTP 500
# (which also fails the health check).
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && apk add --no-cache --virtual .build-deps libcap \
    # The binary may bind ports below 1024 through a file capability.
    # OpenShift's restricted SCC refuses to start binaries with extra
    # capabilities, and port 8080 doesn't need it.
    && setcap -r /usr/local/bin/frankenphp \
    && apk del .build-deps \
    # Caddy keeps its state in /data and /config (XDG_DATA_HOME,
    # XDG_CONFIG_HOME); a random UID in group 0 must be able to write them.
    && chgrp -R 0 /data /config \
    && chmod -R g=u /data /config

COPY files/webhook/ /

WORKDIR /app
USER 1001
EXPOSE 8080

# The health check requests /health, which the webhook answers with 200 when
# it's ready. It replaces the parent image's health check, which uses Caddy's
# admin API (turned off in the Caddyfile).
HEALTHCHECK --interval=10s --timeout=3s --start-period=5s \
    CMD curl -fsS -o /dev/null http://localhost:8080/health || exit 1

# umask 0002: the webhook and the worker run as different UIDs in group 0 and
# share the queue folder, so whatever the webhook creates there must be
# group-writable. Set for the whole process here, because PHP's umask() isn't
# safe in FrankenPHP's threads. exec keeps FrankenPHP as PID 1, so it gets
# SIGTERM and stops gracefully.
ENTRYPOINT ["/bin/sh", "-c", "umask 0002 && exec frankenphp run --config /etc/frankenphp/Caddyfile --adapter caddyfile"]
CMD []


# helfi-search-elasticsearch-crawler: Elastic Open Crawler with PHP, for the
# worker and the full crawl, which run bin/crawler (in /home/app) themselves.
FROM docker.elastic.co/integrations/crawler:latest AS elasticsearch-crawler

USER root
RUN apk add --no-cache php-8.5 php-8.5-pcntl php-8.5-ctype php-8.5-mbstring php-8.5-curl tini \
    # The crawler saves rejected bulk requests in output/failed_payloads,
    # relative to its directory. The image's java user owns /home/app, so a
    # random UID couldn't create it.
    && mkdir -p /home/app/output/failed_payloads \
    && chgrp -R 0 /home/app/output \
    && chmod -R 2775 /home/app/output

USER java
WORKDIR /app
# A random UID has no home directory; HOME would otherwise be /.
ENV HOME=/tmp
# tini forwards signals and reaps the crawler's processes. The project sets the
# command (CMD).
ENTRYPOINT ["/usr/bin/tini", "--"]
CMD ["php", "--version"]


FROM webhook AS test-webhook

USER 0
# Only for the tests: Composer for PHPUnit, outside PATH so the tests can tell
# whether the image itself has Composer.
COPY --from=composer:2 /usr/bin/composer /opt/test/composer
COPY tests/ /tests
WORKDIR /tests
RUN php /opt/test/composer install --no-interaction --no-progress && \
    vendor/bin/phpunit --testsuite webhook


FROM elasticsearch-crawler AS test-elasticsearch-crawler

USER 0
# Only for the tests: what PHPUnit and Composer need, and Composer outside
# PATH as above.
RUN apk add --no-cache php-8.5-phar php-8.5-openssl php-8.5-dom php-8.5-xml \
    php-8.5-xmlwriter php-8.5-iconv
COPY --from=composer:2 /usr/bin/composer /opt/test/composer
COPY tests/ /tests
WORKDIR /tests
RUN php /opt/test/composer install --no-interaction --no-progress && \
    vendor/bin/phpunit --testsuite elasticsearch-crawler
