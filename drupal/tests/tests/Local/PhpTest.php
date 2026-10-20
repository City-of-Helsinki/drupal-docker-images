<?php

declare(strict_types=1);

namespace Tests\Local;

class PhpTest extends \Tests\Shared\PhpTestBase {

  public static function extensionNames() : array {
    return [
      ...parent::extensionNames(),
      ['pcov'],
    ];
  }

  public static function phpFpmPoolSettings() : array {
    return [
      ['pm', 'ondemand'],
      ['pm.max_children', '10'],
      ['pm.process_idle_timeout', '60'],
    ];
  }

}
