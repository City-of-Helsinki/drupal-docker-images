<?php

declare(strict_types=1);

namespace Tests\Shared;

use PHPUnit\Framework\Attributes\DataProvider;

abstract class PhpTestBase extends TestBase {

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

  public function testUploadProgressDisabled() : void {
    $this->assertFalse(filter_var(ini_get('session.upload_progress.enabled'), FILTER_VALIDATE_BOOLEAN));
  }

  public function testPhpVersion() {
    $actual = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
    $this->assertTrue(version_compare(static::$phpVersion, $actual, 'eq'));
  }

  /**
   * Gets the effective php-fpm www pool settings.
   *
   * @return array
   *   The pool settings, keyed by setting name.
   */
  private function getPhpFpmPoolSettings() : array {
    // Add notice log level to get the config.
    $conf = sys_get_temp_dir() . '/php-fpm-dump.conf';
    file_put_contents($conf, sprintf(
      "include=/etc/php%s/php-fpm.conf\n[global]\nlog_level = notice\n",
      static::getPhpShortVersion(),
    ));

    exec(sprintf('php-fpm -tt -y %s 2>&1', escapeshellarg($conf)), $output, $returnCode);
    $this->assertEquals(0, $returnCode, implode("\n", $output));

    $settings = [];
    $section = NULL;
    foreach ($output as $line) {
      if (preg_match('/NOTICE: \[(\w+)\]$/', $line, $matches)) {
        $section = $matches[1];
      }
      elseif ($section === 'www' && preg_match('/NOTICE: \t(\S+) = (.*)$/', $line, $matches)) {
        $settings[$matches[1]] = $matches[2];
      }
    }
    $this->assertNotEmpty($settings, 'Could not parse php-fpm config dump.');
    return $settings;
  }

  #[DataProvider(methodName: 'phpFpmPoolSettings')]
  public function testPhpFpmPoolSetting(string $setting, string $expectedValue) : void {
    $settings = $this->getPhpFpmPoolSettings();
    $this->assertArrayHasKey($setting, $settings);
    $this->assertEquals($expectedValue, $settings[$setting]);
  }

  abstract public static function phpFpmPoolSettings();

}
