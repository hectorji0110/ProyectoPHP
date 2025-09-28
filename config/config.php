<?php
$envFile = __DIR__ . '/config.env';
if (!file_exists($envFile)) {
    throw new Exception(".env not found in config/");
}
$env = parse_ini_file($envFile);

define('DB_HOST', $env['DB_HOST'] ?? '127.0.0.1');
define('DB_NAME', $env['DB_NAME'] ?? 'Proyectophp');
define('DB_USER', $env['DB_USER'] ?? 'root');
define('DB_PASS', $env['DB_PASS'] ?? '');
define('BASE_URL', rtrim($env['BASE_URL'] ?? 'http://localhost', '/'));
define('UPLOAD_DIR', __DIR__ . '/../public/uploads');
