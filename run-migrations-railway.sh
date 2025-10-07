#!/bin/bash

# Скрипт для запуска миграций на Railway
# Используйте: railway run bash run-migrations-railway.sh

echo "=== Railway Migration Script ==="

# Find PHP executable
PHP_CMD="php"
if ! command -v php &> /dev/null; then
    echo "PHP not found in PATH, searching..."
    for path in /usr/bin/php /usr/local/bin/php /opt/php/bin/php /usr/local/php/bin/php; do
        if [ -x "$path" ]; then
            PHP_CMD="$path"
            echo "Found PHP at: $path"
            break
        fi
    done
fi

if [ ! -x "$PHP_CMD" ]; then
    echo "ERROR: PHP not found!"
    echo "Available commands:"
    ls -la /usr/bin/ | grep php
    ls -la /usr/local/bin/ | grep php
    exit 1
fi

echo "Using PHP: $PHP_CMD"
$PHP_CMD --version

echo "=== Running Migrations ==="
$PHP_CMD artisan migrate --force

echo "=== Running Seeders ==="
$PHP_CMD artisan db:seed --force

echo "=== Done! ==="
