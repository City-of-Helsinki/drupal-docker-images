<?php

declare(strict_types=1);

namespace Tests\Webhook;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The helfi-search-webhook image.
 */
class ImageTest extends TestCase {

  public function testProductionPhpIni() : void {
    $this->assertSame(getenv('PHP_INI_DIR') . '/php.ini', php_ini_loaded_file());
    // Errors must be logged, never shown to the client.
    $this->assertContains(ini_get('display_errors'), ['', '0', 'Off']);
    $this->assertSame('1', ini_get('log_errors'));
  }

  #[DataProvider(methodName: 'groupWritableData')]
  public function testWritableByGroupZero(string $path) : void {
    $this->assertDirectoryExists($path);
    $this->assertSame(0, filegroup($path), "$path belongs to group 0");
    $this->assertSame(0020, fileperms($path) & 0020, "$path is group-writable");
  }

  public static function groupWritableData() : array {
    return [
      ['/data'],
      ['/data/caddy'],
      ['/config'],
      ['/config/caddy'],
    ];
  }

  public function testCaddyfileIsValid() : void {
    exec('frankenphp validate --config /etc/frankenphp/Caddyfile --adapter caddyfile 2>&1', $output, $exitCode);
    $this->assertSame(0, $exitCode, implode("\n", $output));
  }

  public function testNoComposer() : void {
    // Applications install their dependencies in a build stage; the images
    // don't need Composer.
    $this->assertFileDoesNotExist('/usr/local/bin/composer');
  }

}
