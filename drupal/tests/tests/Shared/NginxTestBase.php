<?php

declare(strict_types=1);

namespace Tests\Shared;

/**
 * Base class for testing nginx setting.
 */
abstract class NginxTestBase extends TestBase {

  /**
   * The expected nginx worker_processes value.
   */
  abstract protected static function expectedWorkerProcesses() : string;

  /**
   * Captures the arguments the nginx entrypoint starts nginx with.
   *
   * @return string
   *   The value of the -g argument.
   */
  private function getNginxGlobalDirectives() : string {
    $stubDir = sys_get_temp_dir() . '/nginx-stub';
    @mkdir($stubDir);
    file_put_contents("$stubDir/nginx", "#!/bin/sh\nprintf '%s\\n' \"\$@\"\n");
    chmod("$stubDir/nginx", 0755);

    $output = [];
    exec(sprintf('PATH=%s:$PATH sh /entrypoints/99-nginx.sh', escapeshellarg($stubDir)), $output, $returnCode);
    $this->assertEquals(0, $returnCode);

    $index = array_search('-g', $output, TRUE);
    $this->assertNotFalse($index, 'Nginx entrypoint does not pass -g.');
    return $output[$index + 1];
  }

  public function testWorkerProcesses() : void {
    $directives = $this->getNginxGlobalDirectives();

    $this->assertStringContainsString(
      sprintf('worker_processes %s;', static::expectedWorkerProcesses()),
      $directives,
    );

    // Validate nginx settings.
    exec(sprintf('nginx -t -g %s 2>&1', escapeshellarg($directives)), $output, $returnCode);
    $this->assertEquals(0, $returnCode, implode("\n", $output));
  }

}
