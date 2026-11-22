<?php
require_once __DIR__ . '/../security.php';
tambito_admin_api();
$dataFile = __DIR__ . '/../data/categorias.json';
$data = tambito_read_json($dataFile);
$codigo = trim((string)($_POST['codigo'] ?? ''));
$nombre = trim((string)($_POST['nombre'] ?? ''));
$precio = filter_var($_POST['precio'] ?? null, FILTER_VALIDATE_FLOAT);
if (!preg_match('/^[A-Za-z0-9_-]{2,30}$/', $codigo) || $nombre === '' ||
    mb_strlen($nombre) > 150 || $precio === false || $precio <= 0 || $precio > 100000) {
    tambito_fail('Código, nombre o precio inválido.');
}
$found = false;
foreach ($data as &$category) {
    foreach (($category['productos'] ?? []) as &$product) {
        if (($product['codigo'] ?? '') !== $codigo) continue;
        $product['nombre'] = $nombre;
        $product['precio'] = round((float)$precio, 2);
        $image = tambito_upload_image('imagen', __DIR__ . '/../uploads', 'uploads');
        if ($image !== null) $product['imagen_url'] = $image;
        $found = true;
        break 2;
    }
    unset($product);
}
unset($category, $product);
if (!$found) tambito_fail('Producto no encontrado.', 404);
tambito_write_json($dataFile, $data);
echo json_encode(['success' => true]);
