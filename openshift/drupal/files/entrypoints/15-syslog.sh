#!/bin/sh
touch /tmp/drupal.log && chmod a+rw /tmp/drupal.log

tail -f /tmp/drupal.log &
