#!/bin/bash

XDEBUG_INI=/etc/php$PHP_INSTALL_VERSION/conf.d/xdebug.ini

if [ "$XDEBUG_ENABLE" = "true" ]; then
  echo "- Start with Xdebug enabled. Remove XDEBUG_ENABLE=true ENV variable to disable it."
  if [ -f "$XDEBUG_INI" ]; then
    echo "- Already enabled..."
  else
    mv "$XDEBUG_INI".disabled "$XDEBUG_INI"
    touch /tmp/xdebug.log && chmod 666 /tmp/xdebug.log
  fi
else
  echo "- Start with Xdebug disabled. Add XDEBUG_ENABLE=true ENV variable to enable it."
  if [ -f "$XDEBUG_INI" ]; then
    mv "$XDEBUG_INI" "$XDEBUG_INI".disabled
  else
    echo "- Already disabled..."
  fi
fi
