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
$catalog = tambito_read_json(__DIR__ . '/../data/categorias.json');
check(count($catalog) >= 5, 'Cinco categorías de ejemplo');
$codes = [];
$count = 0;
foreach ($catalog as $category => $data) {
    check(is_string($category) && $category !== '', 'Categoría válida');
    check(is_array($data['productos'] ?? null), 'Lista de productos');
    foreach ($data['productos'] as $item) {
        check(!empty($item['codigo']) && !isset($codes[$item['codigo']]), 'Código único');
        $codes[$item['codigo']] = true;
        check(is_numeric($item['precio']) && $item['precio'] > 0, 'Precio de muestra positivo');
        $relative = (string)($item['imagen_url'] ?? '');
        check(str_starts_with($relative, './img/PRODUCTOS-CATEGORIA/'), 'Ruta de imagen local');
        check(is_file(__DIR__ . '/../' . $relative), 'Imagen existente: ' . $relative);
        $count++;
    }
}
check($count === 40, '40 productos ilustrativos');
$file = tempnam(sys_get_temp_dir(), 'tambito-');
try {
    tambito_write_json($file, ['test' => true, 'unicode' => 'español']);
    check(tambito_read_json($file)['test'] === true, 'Escritura de JSON');
} finally {
    if (is_file($file)) unlink($file);
}
check(strlen(tambito_csrf_token()) === 64, 'Longitud del token CSRF');
echo "OK: archivos JSON, persistencia y token CSRF" . PHP_EOL;
