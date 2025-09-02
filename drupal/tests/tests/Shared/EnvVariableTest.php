<?php

declare(strict_types=1);

namespace Tests\Shared;

use PHPUnit\Framework\Attributes\DataProvider;

class EnvVariableTest extends TestBase {

  #[DataProvider(methodName: 'envVariableData')]
  public function testEnvVariable(string $envVariable, string $expectedValue) : void {
    $this->assertEquals($expectedValue, getenv($envVariable));
  }

  public static function envVariableData() : array {
    return [
      ['PATH', '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/app/vendor/bin:/var/www/html/vendor/bin'],
      ['AZURE_SQL_SSL_CA_PATH', '/usr/local/share/ca-certificates/BaltimoreCyberTrustRoot.crt.pem'],
    ];
  }

}
