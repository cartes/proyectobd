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

echo "Re-linking storage (manual absolute link)..."
rm -f public/storage
# Use absolute paths for the symlink to avoid relative path confusion
ln -s $PWD/storage/app/public $PWD/public/storage

echo "Creating debug files..."
echo "Public Access OK" > public/debug_public.txt
echo "Storage Access OK" > storage/app/public/debug_storage.txt

echo "Fixing permissions (final)..."
chmod -R 777 storage bootstrap/cache public

echo "Verifying link and permissions..."
ls -la public/storage
ls -la storage/app/public

echo "Caching config..."
php artisan config:cache
php artisan route:cache

echo "Starting server..."
# Explicitly use php.ini via -c flag and start built-in server via server.php
# This avoids overriding system extension loading via environment variables
php -c php.ini -S 0.0.0.0:$PORT -t public server.php
