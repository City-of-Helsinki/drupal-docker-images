<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EnvVariableTest extends TestCase {

  #[DataProvider(methodName: 'envVariableData')]
  public function testExtension(string $envVariable, string $expectedValue) : void {
    $this->assertEquals($expectedValue, getenv($envVariable));
  }

  public static function envVariableData() : array {
    return [
      ['PATH', '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/app/vendor/bin:/var/www/html/vendor/bin'],
      ['COMPOSER_HOME', '/.composer'],
      ['AZURE_SQL_SSL_CA_PATH', '/usr/local/share/ca-certificates/BaltimoreCyberTrustRoot.crt.pem'],
    ];
  }

}
