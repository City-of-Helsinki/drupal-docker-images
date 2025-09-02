<?php

declare(strict_types=1);

namespace Tests\Shared;

use PHPUnit\Framework\Attributes\DataProvider;

class PhpTest extends TestBase {

  #[DataProvider(methodName: 'extensionNames')]
  public function testExtension(string $extension) : void {
    $this->assertTrue(extension_loaded($extension));
  }

  public static function extensionNames() : array {
    return [
      ['json'],
      ['apcu'],
      ['curl'],
      ['mbstring'],
      ['openssl'],
      ['pdo'],
      ['redis'],
      ['sodium'],
      ['ctype'],
      ['zend opcache'],
      ['tokenizer'],
      ['xml'],
      ['simplexml'],
      ['imagick'],
    ];
  }

  public function testPhpVersion() {
    $actual = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
    $this->assertTrue(version_compare(static::$phpVersion, $actual, 'eq'));
  }

}
