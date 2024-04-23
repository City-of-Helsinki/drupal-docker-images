#!/bin/sh

cd /var/www/html

# @todo Remove this once all projects use monolog.
if ! composer show drupal/monolog -q 2>/dev/null; then
  sudo touch /tmp/drupal.log && sudo chmod a+rw /tmp/drupal.log
  tail -f /tmp/drupal.log &
else
  echo "Found drupal/monolog. Skipping logger entrypoint ..."
fi

