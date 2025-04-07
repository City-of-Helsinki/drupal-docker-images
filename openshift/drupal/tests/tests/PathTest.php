<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase {

  #[DataProvider(methodName: 'directoryData')]
  public function testDirectory(string $path) : void {
    $this->assertTrue(is_dir($path));
  }

  public static function pathData() : array {
    return [
      ['/private_files'],
    ];
  }

}
