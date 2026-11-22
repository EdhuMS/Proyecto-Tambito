<?php
require_once __DIR__ . '/security.php';
tambito_session();
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: login.php', true, 303); exit;
}
if (!hash_equals(tambito_csrf_token(), (string)($_POST['csrf_token'] ?? ''))) {
    $_SESSION['error_message'] = 'Sesión del formulario vencida.';
    header('Location: login.php', true, 303); exit;
}
$now = time();
if (($_SESSION['login_locked_until'] ?? 0) > $now) {
    $_SESSION['error_message'] = 'Demasiados intentos. Espera 15 minutos.';
    header('Location: login.php', true, 303); exit;
}
$email = strtolower(trim((string)($_POST['email'] ?? '')));
$password = (string)($_POST['password'] ?? '');
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100 || $password === '') {
    $_SESSION['error_message'] = 'Credenciales incorrectas.';
    header('Location: login.php', true, 303); exit;
}
require __DIR__ . '/conexion.php';
$stmt = $conn->prepare('SELECT id, nombre, rol, password FROM usuarios WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['login_failures'] = (int)($_SESSION['login_failures'] ?? 0) + 1;
    if ($_SESSION['login_failures'] >= 5) {
        $_SESSION['login_locked_until'] = $now + 900;
        $_SESSION['login_failures'] = 0;
    }
    $_SESSION['error_message'] = 'Credenciales incorrectas.';
    header('Location: login.php', true, 303); exit;
}
session_regenerate_id(true);
unset($_SESSION['login_failures'], $_SESSION['login_locked_until'], $_SESSION['csrf_token']);
$_SESSION['id'] = (int)$user['id'];
$_SESSION['nombre'] = (string)$user['nombre'];
$_SESSION['rol'] = (string)$user['rol'];
header('Location: ' . ($user['rol'] === 'admin' ? 'administracion.php' : 'index.php'), true, 303);
exit;
