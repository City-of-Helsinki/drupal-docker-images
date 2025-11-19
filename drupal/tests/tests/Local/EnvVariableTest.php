<?php

declare(strict_types=1);

namespace Tests\Local;

class EnvVariableTest extends \Tests\Shared\EnvVariableTest {

  public function testSimpletestDbUrl() : void {
    $this->assertNotEmpty(getenv('SIMPLETEST_DB'));
  }

  public function testPhpInstallVersion() : void {
    $this->assertNotEmpty(getenv('PHP_INSTALL_VERSION'));
  }

  public static function envVariableData() : array {
    return [
      ['DRUPAL_DB_NAME', 'drupal'],
      ['DRUPAL_DB_USER', 'drupal'],
      ['DRUPAL_DB_PASS', 'drupal'],
      ['DRUPAL_DB_HOST', 'db'],
      ['DRUPAL_DB_PORT', '3306'],
      ['SIMPLETEST_BASE_URL', 'https://app'],
      ['DRUSH_ALLOW_XDEBUG', '1'],
      ['DEFAULT_USER', 'app'],
      ['DEFAULT_USER_UID', '1000'],
      ['COMPOSER_HOME', '/tmp/.composer'],
    ];
  }

}
