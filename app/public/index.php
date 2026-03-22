<?php
declare(strict_types=1);
require_once __DIR__ . '/../lib/db.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CMD App — Utilities</title>
  <link rel="stylesheet" href="/style.css">
</head>
<body>
<header class="container header">
  <div>
    <h1>CMD App</h1>
    <div class="muted">Utilities (safe command execution)</div>
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
    <h2>Tools</h2>
    <ul>
      <li><a href="/ping.php">Ping</a> — checks reachability (IPv4/IPv6/hostname allowlist).</li>
      <li><a href="/dns.php">DNS lookup</a> — resolves a hostname (A/AAAA records).</li>
      <li><a href="/trace.php">Traceroute</a> — limited hops/timeouts.</li>
      <li><a href="/history.php">History</a> — stores recent tool results in SQLite.</li>
    </ul>
    <p class="muted">Note: commands run without a shell (argv array + bypass_shell).</p>
  </section>
</main>
</body>
</html>
