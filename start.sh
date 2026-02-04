#!/bin/bash
echo "Ensuring storage directories exist..."
mkdir -p storage/app/public/profiles
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views

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
