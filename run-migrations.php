<?php

// Скрипт для запуска миграций на Railway
// Используйте: railway run php run-migrations.php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "Running migrations...\n";
$exitCode = $kernel->call('migrate', ['--force' => true]);

if ($exitCode === 0) {
    echo "Migrations completed successfully!\n";
} else {
    echo "Migrations failed with exit code: $exitCode\n";
}

echo "Running seeders...\n";
$exitCode = $kernel->call('db:seed', ['--force' => true]);

if ($exitCode === 0) {
    echo "Seeders completed successfully!\n";
} else {
    echo "Seeders failed with exit code: $exitCode\n";
}

echo "Done!\n";
