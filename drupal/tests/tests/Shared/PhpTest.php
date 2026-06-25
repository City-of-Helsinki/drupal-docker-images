<?php

declare(strict_types=1);

namespace Tests\Shared;

use PHPUnit\Framework\Attributes\DataProvider;

class PhpTest extends TestBase {

  #[DataProvider(methodName: 'extensionNames')]
  public function testExtension(string $extension) : void {
    $this->assertTrue(extension_loaded($extension));
  }

  public function testIgBinary() : void {
    $file = sprintf('/etc/php%s%s/conf.d/10_igbinary.ini', PHP_MAJOR_VERSION, PHP_MINOR_VERSION);

    $this->assertTrue(file_exists($file));
    $contents = file_get_contents($file);

    // Make sure igbinary is the default apc serializer.
    $this->assertStringContainsString('apc.serializer=igbinary', $contents);
  }

  public static function extensionNames() : array {
    return [
      ['json'],
      ['igbinary'],
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
