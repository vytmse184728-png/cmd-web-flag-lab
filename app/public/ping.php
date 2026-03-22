<?php
declare(strict_types=1);
require_once __DIR__ . '/../lib/run.php';

$host = $_GET['host'] ?? '';
$result = null;
$error = '';

if ($host !== '') {
    try {
        $output = shell_exec("ping -c 3 -W 2 " . $host);
        
        $result = $output;
        
        if (function_exists('log_history')) {
            log_history('ping', $host, 0, (string)$output);
        }
    } catch (Throwable $e) {
        $error = "System Error: " . $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ping Tool — Vulnerable</title>
  <link rel="stylesheet" href="/style.css">
</head>
<body>
<header class="container header">
  <div>
    <h1><a href="/">Network App</a></h1>
    <div class="muted">Diagnostic tools (Lab Mode)</div>
  </div>
</header>

<main class="container">
  <section class="card">
    <h2>Ping Test</h2>
    <form class="row" method="get" action="/ping.php">
      <input class="grow" name="host" value="<?= e($host) ?>" placeholder="127.0.0.1; ls -la">
      <button>Run</button>
      <?php if ($host !== ''): ?><a class="pill" href="/ping.php">Clear</a><?php endif; ?>
    </form>
    <?php if ($error): ?>
      <div class="error"><?= e($error) ?></div>
    <?php endif; ?>
  </section>

  <?php if ($result !== null): ?>
  <section class="card">
    <h3>Command Output</h3>
    <pre class="output-box"><?= e($result) ?></pre>
  </section>
  <?php endif; ?>
</main>
</body>
</html>