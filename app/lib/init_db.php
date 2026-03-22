<?php
declare(strict_types=1);

$path = __DIR__ . '/../storage/history.sqlite';
@mkdir(dirname($path), 0777, true);

$pdo = new PDO('sqlite:' . $path, null, null, [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$pdo->exec('CREATE TABLE IF NOT EXISTS history (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  tool TEXT NOT NULL,
  input TEXT NOT NULL,
  status INTEGER NOT NULL,
  output TEXT NOT NULL,
  created_at TEXT NOT NULL
)');

$pdo->exec('CREATE INDEX IF NOT EXISTS idx_history_created_at ON history(created_at)');

echo "OK\n";
