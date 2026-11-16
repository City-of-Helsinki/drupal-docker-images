<?php

declare(strict_types=1);

namespace Tests\ElasticsearchCrawler;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The helfi-search-elasticsearch-crawler image.
 */
class ImageTest extends TestCase {

  public function testPhpVersion() : void {
    $this->assertSame('8.5', PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION);
  }

  #[DataProvider(methodName: 'extensionData')]
  public function testExtension(string $extension) : void {
    $this->assertTrue(extension_loaded($extension), "$extension is loaded");
  }

  public static function extensionData() : array {
    return [
      // Stopping cleanly on SIGTERM.
      ['pcntl'],
      ['ctype'],
      ['mbstring'],
      ['curl'],
      // Parsing the crawled pages.
      ['dom'],
      ['xml'],
    ];
  }

  public function testErrorsAreLogged() : void {
    $this->assertContains(ini_get('display_errors'), ['', '0', 'Off']);
    $this->assertSame('1', ini_get('log_errors'));
  }

  public function testCrawlerRuns() : void {
    exec('cd /home/app && bin/crawler version 2>&1', $output, $exitCode);
    $this->assertSame(0, $exitCode, implode("\n", $output));
    // The image follows the crawler's latest release, so any version will do.
    $this->assertMatchesRegularExpression('/\d+\.\d+\.\d+/', implode("\n", $output));
  }

  public function testFailedPayloadsFolderIsWritableByGroupZero() : void {
    foreach (['/home/app/output', '/home/app/output/failed_payloads'] as $path) {
      $this->assertDirectoryExists($path);
      $this->assertSame(0, filegroup($path), "$path belongs to group 0");
      $this->assertSame(0020, fileperms($path) & 0020, "$path is group-writable");
    }
  }

  public function testHomeIsWritable() : void {
    // A random UID has no home directory.
    $this->assertSame('/tmp', getenv('HOME'));
  }

  public function testTini() : void {
    $this->assertTrue(is_executable('/usr/bin/tini'), '/usr/bin/tini is executable');
  }

  public function testNoComposer() : void {
    // Applications install their dependencies in a build stage; the images
    // don't need Composer.
    $this->assertFileDoesNotExist('/usr/local/bin/composer');
  }

}
