<?php

declare(strict_types=1);

namespace Tests\Shared;

use PHPUnit\Framework\Attributes\DataProvider;

class PathTest extends TestBase {

  #[DataProvider(methodName: 'directoryData')]
  public function testDirectory(string $path) : void {
    $this->assertTrue(is_dir($path));
  }

  public static function directoryData() : array {
    return [
      ['/private_files'],
    ];
  }

}
