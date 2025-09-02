<?php

declare(strict_types=1);

namespace Local;

class InstalledPackagesTest extends \Tests\Shared\InstalledPackagesTest {

  public static function expectedPackages() : array {
    return [
      ['which gpg'],
      ['which gh'],
    ];
  }

}
