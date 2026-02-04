#!/bin/bash
echo "Starting server..."
# Explicitly use php.ini via -c flag and start built-in server via server.php
# This avoids overriding system extension loading via environment variables
php -c php.ini -S 0.0.0.0:$PORT -t public server.php
