<?php

declare(strict_types=1);

namespace Tests\Shared;

use PHPUnit\Framework\Attributes\DataProvider;

class FileExistsTest extends TestBase {

  #[DataProvider(methodName: 'fileData')]
  public function testEntrypoints(string $path, bool $shouldExist) : void {
      $this->assertEquals($shouldExist, file_exists($path));
  }

  public static function fileData() : array {
    return [
      ['/usr/local/bin/drush-deploy-entrypoint', TRUE],
      ['/usr/local/bin/cron-entrypoint', TRUE],
      ['/usr/local/bin/entrypoint', TRUE],
      ['/usr/local/bin/post-db-replace', TRUE],
    ];
  }

}
