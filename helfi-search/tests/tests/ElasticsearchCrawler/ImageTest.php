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

  public function testLatestSecurityRelease() : void {
    $branch = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
    $json = file_get_contents(
      "https://www.php.net/releases/index.php?json&version=$branch&max=20",
      FALSE,
      stream_context_create(['http' => ['timeout' => 10]]),
    );
    $this->assertNotFalse($json, 'Could not fetch the PHP releases from php.net.');
    $security = [];
    foreach (json_decode($json, TRUE, flags: JSON_THROW_ON_ERROR) as $version => $release) {
      if (in_array('security', $release['tags'] ?? [], TRUE)) {
        $security[] = (string) $version;
      }
    }
    $this->assertNotEmpty($security, "php.net lists no security releases for PHP $branch.");
    usort($security, 'version_compare');
    $latest = end($security);
    $this->assertTrue(
      version_compare(PHP_VERSION, $latest, '>='),
      'PHP ' . PHP_VERSION . " is older than the security release $latest.",
    );
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
