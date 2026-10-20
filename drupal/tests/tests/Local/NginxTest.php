<?php

declare(strict_types=1);

namespace Tests\Local;

class NginxTest extends \Tests\Shared\NginxTestBase {

  protected static function expectedWorkerProcesses() : string {
    return '4';
  }

}
