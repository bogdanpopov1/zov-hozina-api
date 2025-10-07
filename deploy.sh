#!/bin/bash

# Wait for database to be ready
echo "Waiting for database to be ready..."
sleep 10

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Run seeders (optional - only for initial setup)
echo "Running seeders..."
php artisan db:seed --force

# Start the application
echo "Starting application..."
php artisan serve --host 0.0.0.0 --port $PORT
