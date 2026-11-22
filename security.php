<?php
declare(strict_types=1);

/**
 * Shared security for the educational PHP storefront.
 * Authorization is checked on the server, never only in client-side JS.
 */
function tambito_session(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.use_strict_mode', '1');
            session_set_cookie_params([
                'httponly' => true,
                'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                'samesite' => 'Lax',
                'path' => '/',
            ]);
            session_start();
        }
    }
}

function tambito_csrf_token(): string {
    tambito_session();
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function tambito_admin_api(): void {
    tambito_session();
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        header('Allow: POST');
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
        exit;
    }
    if (empty($_SESSION['id']) || ($_SESSION['rol'] ?? '') !== 'admin') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Permisos insuficientes.']);
        exit;
    }
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!is_string($csrf) || !hash_equals(tambito_csrf_token(), $csrf)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Token de seguridad inválido. Actualiza la página.']);
        exit;
    }
}

function tambito_fail(string $message, int $status = 400): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

function tambito_read_json(string $path): array {
    $data = is_file($path) ? json_decode((string)file_get_contents($path), true) : null;
    if (!is_array($data)) {
        tambito_fail('Datos no disponibles.', 500);
    }
    return $data;
}

function tambito_write_json(string $path, array $data): void {
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        tambito_fail('No se pudo serializar el contenido.', 500);
    }
    $temp = tempnam(dirname($path), '.tambito-');
    if ($temp === false) {
        tambito_fail('No se pudo preparar la escritura.', 500);
    }
    try {
        if (file_put_contents($temp, $json, LOCK_EX) === false || !rename($temp, $path)) {
            tambito_fail('No se pudo guardar el contenido.', 500);
        }
    } finally {
        if (is_file($temp)) {
            unlink($temp);
        }
    }
}

function tambito_upload_image(string $field, string $directory, string $publicPrefix): ?string {
    if (!isset($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 3 * 1024 * 1024 || $file['size'] < 1 ||
        !is_uploaded_file($file['tmp_name'])) {
        tambito_fail('Imagen no válida o demasiado grande (máximo 3 MB).');
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime]) || @getimagesize($file['tmp_name']) === false) {
        tambito_fail('Solo se admiten imágenes JPG, PNG y WebP.');
    }
    if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
        tambito_fail('No se pudo preparar el directorio.', 500);
    }
    $name = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], rtrim($directory, '/') . '/' . $name)) {
        tambito_fail('No se pudo almacenar la imagen.', 500);
    }
    return trim($publicPrefix, '/') . '/' . $name;
}
