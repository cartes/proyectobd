#!/bin/bash
echo "Ensuring storage directories exist..."
mkdir -p storage/app/public/profiles
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views

echo "Fixing permissions..."
chmod -R 775 storage bootstrap/cache

echo "Removing broken symlinks..."
rm -rf public/storage

echo "Running migrations..."
php artisan migrate --force

echo "Seeding cities..."
php artisan db:seed --class=CitySeeder --force

echo "Caching config, routes, events and views..."
php artisan optimize

echo "Starting Reverb (WebSockets)..."
php artisan reverb:start --host=0.0.0.0 --port=8080 &

echo "Starting Queue worker..."
php artisan queue:work --tries=3 --timeout=90 &

echo "Starting server..."
# Explicitly use php.ini via -c flag and start built-in server via server.php.
# PHP_CLI_SERVER_WORKERS lets the built-in server handle requests in parallel
# (by default it is single-threaded: one slow request blocks every visitor).
export PHP_CLI_SERVER_WORKERS=${PHP_CLI_SERVER_WORKERS:-8}
php -c php.ini -S 0.0.0.0:$PORT -t public server.php
