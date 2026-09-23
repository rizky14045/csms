#!/usr/bin/env bash
# Run this ON THE SERVER (192.168.18.58) itself, from the deployed app's root directory.
# Running it locally has network round-trip latency per query; running it here
# talks to Postgres over localhost, which is why this is fast where the remote run was not.
set -euo pipefail

cd "$(dirname "$0")"

echo "==> Pending migrations:"
php artisan migrate:status | grep -i "pending" || true

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Clearing caches (config/route/view/permission) so the new code + permissions take effect immediately..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan permission:cache-reset

echo "==> Done. Verifying migration status:"
php artisan migrate:status | tail -20
