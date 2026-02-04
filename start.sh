#!/bin/bash
# Force PHP to scan current directory for .ini files (loading php.ini)
export PHP_INI_SCAN_DIR=:$PWD

echo "Starting with custom PHP configuration..."
php --ini

echo "Running migrations..."
php artisan migrate --force

echo "Linking storage..."
php artisan storage:link

echo "Caching config..."
php artisan config:cache
php artisan route:cache

echo "Starting server..."
php artisan serve --host=0.0.0.0 --port=$PORT
