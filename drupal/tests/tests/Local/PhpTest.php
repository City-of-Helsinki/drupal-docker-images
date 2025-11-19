<?php

declare(strict_types=1);

namespace Local;

class PhpTest extends \Tests\Shared\PhpTest {

  public static function extensionNames() : array {
    return [
      ['pcov'],
    ];
  }

}
