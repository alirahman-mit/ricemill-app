<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

define('LARAVEL_START', microtime(true));

// ── Vercel serverless setup: writable dirs in /tmp ──
$storagePath = $_ENV['LARAVEL_STORAGE_PATH'] ?? $_SERVER['LARAVEL_STORAGE_PATH'] ?? getenv('LARAVEL_STORAGE_PATH');
if ($storagePath) {
    $writableDirs = [
        '/tmp/storage/app/private',
        '/tmp/storage/app/public',
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/views',
        '/tmp/storage/logs',
        '/tmp/bootstrap/cache',
    ];
    foreach ($writableDirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
    }

    // Create SQLite database file
    $dbPath = '/tmp/database.sqlite';
    if (!file_exists($dbPath)) {
        @touch($dbPath);
    }

    // Run standalone migration (raw PDO, no Laravel bootstrap needed)
    $migrationLock = '/tmp/.migrations_done';
    if (!file_exists($migrationLock)) {
        try {
            require __DIR__ . '/../api/migrate.php';
            @touch($migrationLock);
        } catch (\Throwable $e) {
            @file_put_contents('/tmp/storage/logs/migration-error.log', $e->getMessage());
        }
    }

    // Symlink storage
    $storageLink = __DIR__ . '/storage';
    if (!file_exists($storageLink) && !is_link($storageLink)) {
        @symlink('/tmp/storage/app/public', $storageLink);
    }
}

try {
    // Determine if the application is in maintenance mode...
    if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
        require $maintenance;
    }

    // Register the Composer autoloader...
    require_once __DIR__.'/../vendor/autoload.php';

    // Bootstrap Laravel and handle the request...
    /** @var \Illuminate\Foundation\Application $app */
    $app = require __DIR__.'/../bootstrap/app.php';

    $app->handleRequest(\Illuminate\Http\Request::capture());
} catch (\Throwable $e) {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: text/plain');
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n\n";
    echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
}
