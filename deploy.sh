#!/bin/bash

# Exit immediately if a command exits with a non-zero status.
set -e

# Wait for database to be ready
echo "Waiting for database to be ready..."
sleep 10

# Find PHP executable
PHP_CMD="php"
if ! command -v php &> /dev/null; then
    for path in /usr/bin/php /usr/local/bin/php /opt/php/bin/php; do
        if [ -x "$path" ]; then
            PHP_CMD="$path"
            break
        fi
    done
fi

echo "Using PHP: $PHP_CMD"

# Clear any cached configuration
echo "Clearing configuration cache..."
$PHP_CMD artisan config:clear

# Drop all tables and re-run all migrations
echo "Running fresh migrations..."
$PHP_CMD artisan migrate:fresh --force

# Run seeders
echo "Running seeders..."
$PHP_CMD artisan db:seed --force

# Start the application
echo "Starting application..."
$PHP_CMD artisan serve --host 0.0.0.0 --port $PORT