<?php
require_once __DIR__ . '/../security.php';
tambito_admin_api();
$file = __DIR__ . '/../data/categorias.json';
$data = tambito_read_json($file);
$old = trim((string)($_POST['categoria_antigua'] ?? ''));
$new = trim((string)($_POST['categoria_nueva'] ?? ''));
if ($new === '' || mb_strlen($new) > 80 || !isset($data[$old])) {
    tambito_fail('Categoría no válida o inexistente.');
}
if ($old !== $new && isset($data[$new])) {
    tambito_fail('Ya existe una categoría con ese nombre.', 409);
}
if ($old !== $new) {
    $data[$new] = $data[$old];
    unset($data[$old]);
    tambito_write_json($file, $data);
}
echo json_encode(['success' => true]);
