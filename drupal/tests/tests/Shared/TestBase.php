<?php

declare(strict_types=1);

namespace Tests\Shared;

use PHPUnit\Framework\TestCase;

abstract class TestBase extends TestCase {

  protected static ?string $phpVersion = NULL;

  public static function getPhpVersion() : string {
    if (static::$phpVersion === NULL) {
      static::$phpVersion = getenv('PHP_VERSION');
    }
    return static::$phpVersion;
  }

  public static function getPhpShortVersion() : string {
    return str_replace('.', '', static::getPhpVersion());
  }

  protected function setUp(): void {
    parent::setUp();

    static::$phpVersion = static::getPhpVersion();
    $this->assertNotEmpty(static::$phpVersion);
  }

}
