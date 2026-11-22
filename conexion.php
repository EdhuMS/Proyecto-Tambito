<?php
declare(strict_types=1);
// All credentials are loaded from a local, untracked file.
$configPath = __DIR__ . '/config.local.php';
if (!is_file($configPath)) {
    error_log('Tambito: missing local database configuration.');
    http_response_code(503);
    exit('Base de datos no configurada. Consulta el README del proyecto.');
}
$config = require $configPath;
try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli(
        (string)$config['host'],
        (string)$config['user'],
        (string)$config['password'],
        (string)$config['database'],
        (int)($config['port'] ?? 3306)
    );
    $conn->set_charset('utf8mb4');
} catch (Throwable $exception) {
    error_log('Tambito DB connection failed: ' . $exception->getMessage());
    http_response_code(503);
    exit('Servicio de base de datos temporalmente no disponible.');
}
