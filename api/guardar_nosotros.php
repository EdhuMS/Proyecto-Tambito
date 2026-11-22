<?php
require_once __DIR__ . '/../security.php';
tambito_admin_api();
$file = __DIR__ . '/../data/nosotros.json';
$current = tambito_read_json($file);
$new = [];
foreach (['nosotros', 'mision', 'vision'] as $key) {
    $title = trim((string)($_POST[$key . '_titulo'] ?? ''));
    $desc = trim((string)($_POST[$key . '_descripcion'] ?? ''));
    if ($title === '' || strlen($title) > 150 || strlen($desc) > 4000) {
        tambito_fail('Título o descripción inválida.');
    }
    $image = tambito_upload_image($key . '_imagen', __DIR__ . '/../uploads', 'uploads');
    $new[$key] = [
        'titulo' => $title,
        'descripcion' => $desc,
        'imagen_url' => $image ?? ($current[$key]['imagen_url'] ?? 'img/default-icon.png')
    ];
}
tambito_write_json($file, $new);
echo json_encode(['success' => true]);
