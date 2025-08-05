<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class HookEntrypointTest extends TestCase {

  #[DataProvider(methodName: 'entrypointData')]
  public function testEntrypoints(string $path) : void {
      $returnCode = NULL;
      exec($path, result_code: $returnCode);
      $this->assertEquals(0, $returnCode);
  }

  public static function entrypointData() : array {
    return [
      ['/usr/local/bin/drush-deploy-entrypoint'],
      ['/usr/local/bin/post-db-replace'],
    ];
  }

}
