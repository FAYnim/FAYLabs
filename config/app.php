<?php
// ============================================================
// App Configuration
// ============================================================

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

// Load .env file
if (!function_exists('loadEnv')) {
    function loadEnv(string $path): void
    {
        if (!file_exists($path)) return;

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) continue;
            if (!str_contains($line, '=')) continue;

            [$key, $value] = explode('=', $line, 2);
            $key   = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");

            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key]    = $value;
                $_SERVER[$key] = $value;
                putenv("{$key}={$value}");
            }
        }
    }
}

loadEnv(ROOT_PATH . '/.env');

// App constants
if (!defined('APP_NAME')) {
    define('APP_NAME', $_ENV['APP_NAME'] ?? 'FAY Labs');
}
if (!defined('APP_URL')) {
    define('APP_URL',  rtrim($_ENV['APP_URL'] ?? 'http://localhost/faydev/faylabs-dashboard', '/'));
}
if (!defined('APP_ENV')) {
    define('APP_ENV',  $_ENV['APP_ENV']  ?? 'production');
}
if (!defined('BASE_PATH')) {
    define('BASE_PATH', rtrim($_ENV['BASE_PATH'] ?? '', '/'));
}
