<?php

declare(strict_types=1);

namespace Tests\OpenShift;

class NginxTest extends \Tests\Shared\NginxTestBase {

  protected static function expectedWorkerProcesses() : string {
    return 'auto';
  }

}
