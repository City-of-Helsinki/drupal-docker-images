<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PhpTest extends TestCase {

  protected string $shortVersion;
  protected string $phpVersion;

  protected function setUp(): void {
    $this->shortVersion = getenv('PHP_SHORT_VERSION');
    $this->phpVersion = getenv('PHP_VERSION');
    $this->assertNotEmpty($this->shortVersion);
    $this->assertNotEmpty($this->phpVersion);
  }

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
    $this->assertTrue(version_compare($this->phpVersion, $actual, 'eq'));
  }

}
