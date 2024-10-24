#!/bin/sh

if echo "$APP_ENV" | grep -Eq '(local|dev|development|test|testing|stage|staging)'; then
  echo 'add_header  X-Robots-Tag "noindex, nofollow";' >> /etc/nginx/conf.d/global-headers
fi

echo "Start up Nginx..."

nginx -g 'daemon off;'
