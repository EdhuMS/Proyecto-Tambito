<?php
require_once __DIR__ . '/../security.php';
tambito_admin_api();
$fields = ['telefono', 'email', 'instagram_url', 'instagram_text', 'facebook_url', 'facebook_text',
           'tiktok_url', 'tiktok_text', 'horario'];
$contact = [];
foreach ($fields as $key) {
    $value = trim((string)($_POST['contacto_' . $key] ?? ''));
    if (strlen($value) > 250) tambito_fail('Uno de los campos excede 250 caracteres.');
    $contact[$key] = $value;
}
if ($contact['email'] !== '' && !filter_var($contact['email'], FILTER_VALIDATE_EMAIL)) {
    tambito_fail('Correo electrónico inválido.');
}
foreach (['instagram_url', 'facebook_url', 'tiktok_url'] as $key) {
    if ($contact[$key] !== '' && (!filter_var($contact[$key], FILTER_VALIDATE_URL) ||
       !in_array(strtolower((string)parse_url($contact[$key], PHP_URL_SCHEME)), ['https'], true))) {
        tambito_fail('Las redes sociales requieren URLs HTTPS válidas.');
    }
}
tambito_write_json(__DIR__ . '/../data/contacto.json', ['contacto' => $contact]);
echo json_encode(['success' => true]);
