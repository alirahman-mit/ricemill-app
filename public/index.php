<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

define('LARAVEL_START', microtime(true));

try {
    // Determine if the application is in maintenance mode...
    if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
        require $maintenance;
    }

    // Register the Composer autoloader...
    require __DIR__.'/../vendor/autoload.php';

    // Bootstrap Laravel and handle the request...
    /** @var \Illuminate\Foundation\Application $app */
    $app = require_once __DIR__.'/../bootstrap/app.php';

    $app->handleRequest(\Illuminate\Http\Request::capture());
} catch (\Throwable $e) {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: text/plain');
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n\n";
    echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
}

