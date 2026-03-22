#!/bin/sh
set -eu

: "${CYBER_RANGE_FLAG:?CYBER_RANGE_FLAG env is required}"

DB_PATH="/srv/app/app/storage/history.sqlite"
FLAG_PATH="/srv/app/app/public/cyber_range_flag.txt"

mkdir -p /srv/app/app/storage

if [ ! -f "$DB_PATH" ]; then
  echo "[init] creating sqlite history db..."
  php /srv/app/app/lib/init_db.php
fi

printf '%s\n' "$CYBER_RANGE_FLAG" > "$FLAG_PATH"

echo "[start] php built-in server on :8081"
exec php -S 0.0.0.0:8081 -t /srv/app/app/public