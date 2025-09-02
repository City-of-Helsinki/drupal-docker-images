<?php

declare(strict_types=1);

namespace Tests\Local;

class FileExistsTest extends \Tests\Shared\FileExistsTest {

  public static function fileData() : array {
    return [
      ['/etc/ssl/certs/cert.crt', TRUE],
      ['/etc/ssl/private/cert.key', TRUE],
      ['/etc/my.cnf.d/client.cnf', TRUE],
      ['/etc/nginx/http.d/ssl-proxy.conf', TRUE],
      ['/usr/local/bin/drush', TRUE],
      ['/entrypoints/00-umask.sh', TRUE],
      ['/entrypoints/15-xdebug.sh', TRUE],
      // Opcache optimizations should not exist.
      ['/etc/php' . static::$phpVersion . '/conf.d/opcache-recommended.ini', FALSE],
    ];
  }

}
