<?php

declare(strict_types=1);

namespace Tests\OpenShift;

class PhpTest extends \Tests\Shared\PhpTestBase {

  public static function phpFpmPoolSettings() : array {
    return [
      ['pm', 'dynamic'],
      ['pm.max_children', '60'],
      ['pm.start_servers', '15'],
      ['pm.min_spare_servers', '15'],
      ['pm.max_spare_servers', '45'],
      ['pm.max_requests', '500'],
    ];
  }

}
