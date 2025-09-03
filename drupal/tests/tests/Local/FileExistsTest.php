<?php

declare(strict_types=1);

namespace Tests\Local;

class FileExistsTest extends \Tests\Shared\FileExistsTest {

  public static function fileData() : array {
    return [
      ['/etc/nginx/certs/cert.crt', TRUE],
      ['/etc/nginx/certs/cert.key', TRUE],
      ['/etc/my.cnf.d/client.cnf', TRUE],
      ['/etc/nginx/http.d/ssl-proxy.conf', TRUE],
      ['/usr/local/bin/drush', TRUE],
      ['/entrypoints/00-umask.sh', TRUE],
      ['/entrypoints/15-xdebug.sh', TRUE],
      ['/etc/php' . static::getPhpShortVersion() . '/conf.d/xdebug.ini', TRUE],
      // Opcache optimizations should not exist.
      ['/etc/php' . static::getPhpShortVersion() . '/conf.d/opcache-recommended.ini', FALSE],
    ];
  }

}
