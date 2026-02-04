#!/bin/bash
echo "Ensuring directories exist..."
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views

echo "Fixing permissions (aggressive)..."
chmod -R 777 storage bootstrap/cache
chmod -R 777 public

echo "Running migrations..."
php artisan migrate --force

echo "Re-linking storage..."
rm -f public/storage
php artisan storage:link

echo "Verifying link..."
ls -la public/storage

echo "Caching config..."
php artisan config:cache
php artisan route:cache

echo "Starting server..."
# Explicitly use php.ini via -c flag and start built-in server via server.php
# This avoids overriding system extension loading via environment variables
php -c php.ini -S 0.0.0.0:$PORT -t public server.php
