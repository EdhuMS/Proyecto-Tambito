<?php
require_once __DIR__ . '/../security.php';
tambito_admin_api();
$dataFile = __DIR__ . '/../data/categorias.json';
$data = tambito_read_json($dataFile);

$categoria = trim((string)($_POST['categoria'] ?? ''));
$codigo = trim((string)($_POST['codigo'] ?? ''));
$nombre = trim((string)($_POST['nombre'] ?? ''));
$precio = filter_var($_POST['precio'] ?? null, FILTER_VALIDATE_FLOAT);
if ($categoria === '' || mb_strlen($categoria) > 80 || $nombre === '' || mb_strlen($nombre) > 150 ||
    !preg_match('/^[A-Za-z0-9_-]{2,30}$/', $codigo) || $precio === false || $precio <= 0 || $precio > 100000) {
    tambito_fail('Categoría, código, nombre o precio inválido.');
}
foreach ($data as $group) {
    foreach (($group['productos'] ?? []) as $item) {
        if (($item['codigo'] ?? '') === $codigo) {
            tambito_fail('Ya existe un producto con ese código.', 409);
        }
    }
}
$image = tambito_upload_image('imagen', __DIR__ . '/../uploads', 'uploads');
if ($image === null) {
    tambito_fail('Selecciona una imagen JPG, PNG o WebP.');
}
if (!isset($data[$categoria])) {
    $data[$categoria] = ['productos' => []];
}
$data[$categoria]['productos'][] = [
    'codigo' => $codigo, 'nombre' => $nombre,
    'precio' => round((float)$precio, 2), 'imagen_url' => $image
];
tambito_write_json($dataFile, $data);
echo json_encode(['success' => true]);
