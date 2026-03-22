<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';

/**
 * Strict allowlist for host inputs: IPv4, IPv6, or DNS name.
 */
function validate_host(string $host): bool {
  $host = trim($host);
  if ($host === '') return false;
  if (filter_var($host, FILTER_VALIDATE_IP)) return true;
  // DNS name (letters/digits/hyphen/dot), 1..253, no leading/trailing dot
  if (strlen($host) > 253) return false;
  if ($host[0] === '.' || $host[strlen($host)-1] === '.') return false;
  return (bool)preg_match('/^[A-Za-z0-9][A-Za-z0-9.-]*[A-Za-z0-9]$/', $host);
}

/**
 * Run a command without invoking a shell (safe).
 * Returns [exitCode, output].
 */
function run_cmd(array $argv, int $timeoutSec = 5): array {
  $descriptors = [
    1 => ['pipe', 'w'],
    2 => ['pipe', 'w'],
  ];

  $proc = proc_open($argv, $descriptors, $pipes, null, null, ['bypass_shell' => true]);
  if (!is_resource($proc)) {
    return [127, 'Failed to start process.'];
  }

  stream_set_blocking($pipes[1], true);
  stream_set_blocking($pipes[2], true);

  $start = time();
  $out = '';
  $err = '';
  while (true) {
    $status = proc_get_status($proc);
    if (!$status['running']) {
      break;
    }
    if (time() - $start > $timeoutSec) {
      proc_terminate($proc);
      $err .= "\n[timeout] Process terminated.";
      break;
    }
    usleep(100_000);
  }

  $out .= stream_get_contents($pipes[1]) ?: '';
  $err .= stream_get_contents($pipes[2]) ?: '';
  fclose($pipes[1]);
  fclose($pipes[2]);

  $code = proc_close($proc);
  $combined = trim($out . (strlen($err) ? "\n" . $err : ""));
  return [$code, $combined];
}

function log_history(string $tool, string $input, int $status, string $output): void {
  $pdo = db();
  $stmt = $pdo->prepare('INSERT INTO history (tool, input, status, output, created_at) VALUES (:t,:i,:s,:o,:c)');
  $stmt->execute([
    ':t' => $tool,
    ':i' => $input,
    ':s' => $status,
    ':o' => $output,
    ':c' => gmdate('c'),
  ]);
}
