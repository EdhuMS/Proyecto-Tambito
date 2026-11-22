<?php
declare(strict_types=1);
require __DIR__ . '/../security.php';

function check(bool $condition, string $message): void {
    if (!$condition) {
        fwrite(STDERR, "FAIL: " . $message . PHP_EOL);
        exit(1);
    }
}
foreach (glob(__DIR__ . '/../data/*.json') as $file) {
    check(is_array(json_decode((string)file_get_contents($file), true)), 'JSON inválido: ' . $file);
}
check(is_array(tambito_read_json(__DIR__ . '/../data/categorias.json')), 'Lectura de catálogo');
$file = tempnam(sys_get_temp_dir(), 'tambito-');
try {
    tambito_write_json($file, ['test' => true, 'unicode' => 'español']);
    check(tambito_read_json($file)['test'] === true, 'Escritura de JSON');
} finally {
    if (is_file($file)) unlink($file);
}
check(strlen(tambito_csrf_token()) === 64, 'Longitud del token CSRF');
echo "OK: archivos JSON, persistencia y token CSRF" . PHP_EOL;
