#!/bin/bash
echo "Fixing permissions..."
chmod -R 775 storage bootstrap/cache
chmod -R 775 public

echo "Running migrations..."
php artisan migrate --force

echo "Linking storage..."
php artisan storage:link

echo "Caching config..."
php artisan config:cache
php artisan route:cache

echo "Starting server..."
# Explicitly use php.ini via -c flag and start built-in server via server.php
# This avoids overriding system extension loading via environment variables
php -c php.ini -S 0.0.0.0:$PORT -t public server.php
