<?php
declare(strict_types=1);
require_once __DIR__ . '/../lib/db.php';

$pdo = db();
$limit = (int)($_GET['limit'] ?? 50);
$limit = ($limit >= 10 && $limit <= 200) ? $limit : 50;

$stmt = $pdo->prepare('SELECT id, tool, input, status, output, created_at FROM history ORDER BY id DESC LIMIT :l');
$stmt->bindValue(':l', $limit, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>History</title>
  <link rel="stylesheet" href="/style.css">
</head>
<body>
<header class="container header">
  <div>
    <h1><a href="/">CMD App</a></h1>
    <div class="muted">History (SQLite)</div>
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
    <h2>Recent runs</h2>
    <form class="row" method="get" action="/history.php">
      <label class="muted">Rows</label>
      <select name="limit">
        <?php foreach ([50,100,150,200] as $n): ?>
          <option value="<?= $n ?>" <?= $limit===$n?'selected':'' ?>><?= $n ?></option>
        <?php endforeach; ?>
      </select>
      <button>Reload</button>
    </form>
  </section>

  <?php foreach ($rows as $r): ?>
  <section class="card">
    <div class="row spaced">
      <div>
        <strong>#<?= (int)$r['id'] ?></strong>
        <span class="pill"><?= e((string)$r['tool']) ?></span>
        <span class="muted"><?= e((string)$r['created_at']) ?></span>
      </div>
      <div class="muted">exit <?= (int)$r['status'] ?></div>
    </div>
    <div class="muted">input: <code><?= e((string)$r['input']) ?></code></div>
    <pre><?= e((string)$r['output']) ?></pre>
  </section>
  <?php endforeach; ?>

  <?php if (count($rows) === 0): ?>
    <section class="card"><div class="muted">No history yet.</div></section>
  <?php endif; ?>
</main>
</body>
</html>
