<?php

declare(strict_types=1);

namespace Tests\OpenShift;

class EnvVariableTest extends \Tests\Shared\EnvVariableTest {

  public static function envVariableData() : array {
    return [
      ['COMPOSER_HOME', '/.composer'],
    ];
  }

}
