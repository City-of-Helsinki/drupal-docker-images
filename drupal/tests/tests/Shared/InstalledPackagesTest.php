<?php

declare(strict_types=1);

namespace Tests\Shared;

use PHPUnit\Framework\Attributes\DataProvider;

class InstalledPackagesTest extends TestBase {

  #[DataProvider(methodName: 'expectedPackages')]
  public function testPackages(string $packageName) : void {
    $returnCode = NULL;
    exec($packageName, result_code: $returnCode);
    $this->assertEquals(0, $returnCode);
  }

  public static function expectedPackages() : array {
    return [
      ['which git'],
      ['which patch'],
      ['which mysql'],
      ['which bash'],
      ['which jq'],
      ['which make'],
      ['php-fpm --test'],
    ];
  }

}
