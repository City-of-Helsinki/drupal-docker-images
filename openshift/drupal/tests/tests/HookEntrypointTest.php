<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class HookEntrypointTest extends TestCase {

  #[DataProvider(methodName: 'entrypointData')]
  public function testEntrypoints(string $path) : void {
      $this->assertTrue(file_exists($path));
  }

  public static function entrypointData() : array {
    return [
      ['/usr/local/bin/drush-deploy-entrypoint'],
      ['/usr/local/bin/cron-entrypoint'],
      ['/usr/local/bin/entrypoint'],
      ['/usr/local/bin/post-db-replace'],
    ];
  }

}
