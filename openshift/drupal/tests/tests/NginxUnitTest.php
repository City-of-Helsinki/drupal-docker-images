<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class NginxUnitTest extends TestCase {

  #[DataProvider(methodName: 'configNames')]
  public function testConfigExists(string $conf) : void {
    $this->assertFileExists($conf);
  }

  public static function configNames() : array {
    return [
      ['/etc/nginx/nginx.conf'],
      ['/etc/nginx/fastcgi.conf'],
      ['/etc/nginx/http.d/default.conf'],
      ['/etc/nginx/conf.d/global-headers'],
      ['/etc/nginx/conf.d/custom.locations'],
    ];
  }

  public function testNginxCommand() {
    $returnCode = NULL;
    exec('nginx -t', result_code: $returnCode);
    $this->assertEquals(0, $returnCode);
  }

}
