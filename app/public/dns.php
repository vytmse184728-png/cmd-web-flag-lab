<?php
declare(strict_types=1);
require_once __DIR__ . '/../lib/run.php';

$host = trim((string)($_GET['host'] ?? ''));
$result = null;
$error = '';

if ($host !== '') {
  if (!validate_host($host) || filter_var($host, FILTER_VALIDATE_IP)) {
    $error = 'Enter a DNS name (not an IP).';
  } else {
    // Use nslookup (simple & available)
    [$code, $out] = run_cmd(['nslookup', $host], 6);
    $result = $out;
    log_history('dns', $host, $code, $out);
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DNS lookup</title>
  <link rel="stylesheet" href="/style.css">
</head>
<body>
<header class="container header">
  <div>
    <h1><a href="/">CMD App</a></h1>
    <div class="muted">DNS lookup</div>
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
    <h2>DNS lookup</h2>
    <form class="row" method="get" action="/dns.php">
      <input class="grow" name="host" value="<?= e($host) ?>" placeholder="example.com">
      <button>Run</button>
      <?php if ($host !== ''): ?><a class="muted" href="/dns.php">Clear</a><?php endif; ?>
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
