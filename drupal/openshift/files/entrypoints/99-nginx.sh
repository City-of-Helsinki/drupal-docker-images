#!/bin/sh
echo "Start up Nginx..."

nginx -g "daemon off; worker_processes ${NGINX_WORKER_PROCESSES:-auto};"
