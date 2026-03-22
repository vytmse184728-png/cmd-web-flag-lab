<?php
declare(strict_types=1);
require_once __DIR__ . '/../lib/run.php';

$host = trim((string)($_GET['host'] ?? ''));
$result = null;
$error = '';

if ($host !== '') {
  if (!validate_host($host)) {
    $error = 'Invalid host.';
  } else {
    // traceroute: max 12 hops, wait 1s each
    [$code, $out] = run_cmd(['traceroute', '-m', '12', '-w', '1', $host], 10);
    $result = $out;
    log_history('trace', $host, $code, $out);
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Traceroute</title>
  <link rel="stylesheet" href="/style.css">
</head>
<body>
<header class="container header">
  <div>
    <h1><a href="/">CMD App</a></h1>
    <div class="muted">Traceroute</div>
  </div>
  <nav class="nav">
    <a class="pill" href="/">Home</a>
    <a class="pill" href="/ping.php">Ping</a>
    <a class="pill" href="/dns.php">DNS</a>
    <a class="pill" href="/trace.php">Trace</a>
    <a class="pill" href="/history.php">History</a>
  </nav>
</header>

<main class="container">
  <section class="card">
    <h2>Traceroute</h2>
    <form class="row" method="get" action="/trace.php">
      <input class="grow" name="host" value="<?= e($host) ?>" placeholder="example.com or 1.1.1.1">
      <button>Run</button>
      <?php if ($host !== ''): ?><a class="muted" href="/trace.php">Clear</a><?php endif; ?>
    </form>
    <?php if ($error): ?>
      <div class="error"><?= e($error) ?></div>
    <?php endif; ?>
  </section>

  <?php if ($result !== null): ?>
  <section class="card">
    <h3>Output</h3>
    <pre><?= e($result) ?></pre>
  </section>
  <?php endif; ?>
</main>
</body>
</html>
