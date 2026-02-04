#!/bin/bash
echo "Fixing permissions..."
chmod -R 775 storage bootstrap/cache

echo "Running migrations..."
php artisan migrate --force

echo "Caching config..."
php artisan config:cache
php artisan route:cache

echo "Starting server..."
# Explicitly use php.ini via -c flag and start built-in server via server.php
php -c php.ini -S 0.0.0.0:$PORT -t public server.php
