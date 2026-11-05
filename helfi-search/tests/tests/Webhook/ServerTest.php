<?php

declare(strict_types=1);

namespace Tests\Webhook;

use PHPUnit\Framework\TestCase;

/**
 * Starts the server the way the image does and sends it requests.
 */
class ServerTest extends TestCase {

  private const string FRONT_CONTROLLER = <<<'PHP'
    <?php
    if ($_SERVER['REQUEST_URI'] === '/fatal') {
      undefined_function();
    }
    header('Content-Type: application/json');
    echo json_encode([
      'uri' => $_SERVER['REQUEST_URI'],
      'umask' => sprintf('%04o', umask()),
    ]);
    PHP;

  /** @var resource|null */
  private static $server = NULL;

  public static function setUpBeforeClass() : void {
    if (!is_dir('/app/public')) {
      mkdir('/app/public', 0755, TRUE);
    }
    file_put_contents('/app/public/index.php', self::FRONT_CONTROLLER);
    file_put_contents('/app/public/secret.txt', 'secret');

    // The image's ENTRYPOINT. A few PHP threads only: under QEMU emulation
    // (platforms other than the build machine's), PHP can't create a timer
    // for each of FrankenPHP's default two threads per CPU.
    self::$server = proc_open(
      ['/bin/sh', '-c', 'umask 0002 && exec frankenphp run --config /etc/frankenphp/Caddyfile --adapter caddyfile'],
      [0 => ['file', '/dev/null', 'r'], 1 => ['file', '/tmp/server.log', 'a'], 2 => ['file', '/tmp/server.log', 'a']],
      $pipes,
      NULL,
      ['FRANKENPHP_CONFIG' => 'num_threads 4'] + getenv(),
    );

    for ($i = 0; $i < 100; $i++) {
      if (@fsockopen('127.0.0.1', 8080)) {
        return;
      }
      usleep(100_000);
    }
    throw new \RuntimeException('The server did not start: ' . file_get_contents('/tmp/server.log'));
  }

  public static function tearDownAfterClass() : void {
    if (self::$server !== NULL) {
      proc_terminate(self::$server);
      proc_close(self::$server);
    }
  }

  /**
   * @return array{0: int, 1: string}
   *   The status code and the body.
   */
  private function get(string $path) : array {
    $body = file_get_contents('http://127.0.0.1:8080' . $path, FALSE, stream_context_create([
      'http' => ['ignore_errors' => TRUE, 'timeout' => 5],
    ]));
    preg_match('{^HTTP/\S+ (\d+)}', $http_response_header[0] ?? '', $matches);
    return [(int) ($matches[1] ?? 0), (string) $body];
  }

  public function testEveryPathGoesToTheFrontController() : void {
    foreach (['/', '/health', '/some/path?x=1', '/index.php', '/secret.txt'] as $path) {
      [$status, $body] = $this->get($path);
      $this->assertSame(200, $status, $path);
      $this->assertSame($path, json_decode($body, TRUE)['uri'] ?? NULL, "$path reaches index.php with its original URI");
    }
  }

  public function testUmask() : void {
    [, $body] = $this->get('/');
    $this->assertSame('0002', json_decode($body, TRUE)['umask']);
  }

  public function testFatalErrorGivesServerError() : void {
    [$status, $body] = $this->get('/fatal');
    $this->assertSame(500, $status);
    $this->assertStringNotContainsString('undefined_function', $body, 'Error details are not shown to the client.');
  }

}
