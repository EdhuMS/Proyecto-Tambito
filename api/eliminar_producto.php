<?php
require_once __DIR__ . '/../security.php';
tambito_admin_api();
$file = __DIR__ . '/../data/categorias.json';
$data = tambito_read_json($file);
$code = trim((string)($_POST['codigo'] ?? ''));
if (!preg_match('/^[A-Za-z0-9_-]{2,30}$/', $code)) {
    tambito_fail('Código no válido.');
}
$found = false;
foreach ($data as &$category) {
    $items = $category['productos'] ?? [];
    foreach ($items as $idx => $item) {
        if (($item['codigo'] ?? '') === $code) {
            array_splice($items, $idx, 1);
            $category['productos'] = $items;
            $found = true;
            break 2;
        }
    }
}
unset($category);
if (!$found) tambito_fail('Producto no encontrado.', 404);
tambito_write_json($file, $data);
// Files are intentionally not deleted: other records may reuse the same image.
echo json_encode(['success' => true]);
