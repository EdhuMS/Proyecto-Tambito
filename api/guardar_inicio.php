<?php
require_once __DIR__ . '/../security.php';
tambito_admin_api();
$file = __DIR__ . '/../data/inicio.json';
$current = tambito_read_json($file);
$slogan = trim((string)($_POST['eslogan'] ?? ''));
if (mb_strlen($slogan) > 200) tambito_fail('El eslogan es demasiado largo.');
$featured = [];
for ($i = 0; $i < 5; $i++) {
    $name = trim((string)($_POST['categoria_' . $i . '_nombre'] ?? ''));
    if (mb_strlen($name) > 100) tambito_fail('Nombre de categoría demasiado largo.');
    $image = tambito_upload_image('categoria_' . $i . '_imagen', __DIR__ . '/../uploads', 'uploads');
    $existing = $current['categorias_destacadas'][$i]['imagen_url'] ?? '';
    // Do not trust the browser's *_imagen_actual hidden input.
    $featured[] = ['nombre' => $name, 'imagen_url' => $image ?? $existing];
}
tambito_write_json($file, ['eslogan' => $slogan, 'categorias_destacadas' => $featured]);
echo json_encode(['success' => true]);
