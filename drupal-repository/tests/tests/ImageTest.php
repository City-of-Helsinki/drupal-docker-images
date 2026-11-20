<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The drupal-repository image.
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
      ['openssl'],
      ['json'],
      ['phar'],
      ['mbstring'],
      ['apcu'],
      ['zip'],
      ['simplexml'],
      ['iconv'],
    ];
  }

  public function testPhpOverrides() : void {
    // The session extension isn't installed, so check the file itself.
    $this->assertStringEqualsFile('/etc/php85/conf.d/php-overrides.ini', "session.upload_progress.enabled=Off\n");
  }

  #[DataProvider(methodName: 'commandData')]
  public function testCommand(string $command) : void {
    exec("$command 2>&1", $output, $exitCode);
    $this->assertSame(0, $exitCode, implode("\n", $output));
  }

  public static function commandData() : array {
    return [
      // Satis is built with Composer, which needs git to clone the packages.
      ['composer --version'],
      ['git --version'],
      ['make --version'],
      ['webhook -version'],
    ];
  }

  #[DataProvider(methodName: 'envData')]
  public function testEnvVariable(string $name, string $value) : void {
    $this->assertSame($value, getenv($name));
  }

  public static function envData() : array {
    return [
      ['COMPOSER_HOME', '/.composer'],
      ['PROJECT_DIR', '/var/www/html'],
      ['COMPOSER_CACHE_DIR', '/tmp'],
    ];
  }

  #[DataProvider(methodName: 'worldWritableData')]
  public function testWorldWritable(string $path) : void {
    // OpenShift runs the container as a random UID.
    $this->assertDirectoryExists($path);
    $this->assertSame(0777, fileperms($path) & 0777, "$path is writable by everyone");
  }

  public static function worldWritableData() : array {
    return [
      ['/.composer'],
      ['/tmp/repo'],
      ['/tmp/repo/github.com'],
    ];
  }

  #[DataProvider(methodName: 'executableData')]
  public function testExecutable(string $path) : void {
    $this->assertTrue(is_executable($path), "$path is executable");
  }

  public static function executableData() : array {
    return [
      ['/sbin/tini'],
      ['/usr/local/bin/entrypoint'],
      ['/usr/local/bin/webhook'],
      ['/entrypoints/10-fix-permission.sh'],
      ['/entrypoints/20-queue.sh'],
      ['/entrypoints/99-webhook.sh'],
    ];
  }

}
