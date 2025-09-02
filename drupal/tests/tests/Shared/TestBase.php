<?php

declare(strict_types=1);

namespace Tests\Shared;

use PHPUnit\Framework\TestCase;

abstract class TestBase extends TestCase {

  protected static ?string $phpVersion = NULL;

  protected function setUp(): void {
    static::$phpVersion = getenv('PHP_VERSION');
    $this->assertNotEmpty(static::$phpVersion);
  }

}
