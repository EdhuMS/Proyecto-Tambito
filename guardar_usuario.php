<?php
require_once __DIR__ . '/security.php';
tambito_session();
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: registro.php', true, 303); exit;
}
if (!hash_equals(tambito_csrf_token(), (string)($_POST['csrf_token'] ?? ''))) {
    $_SESSION['error_message_registro'] = 'Sesión de formulario vencida. Vuelve a intentarlo.';
    header('Location: registro.php', true, 303); exit;
}
$nombre = trim((string)($_POST['nombre'] ?? ''));
$apellido = trim((string)($_POST['apellido'] ?? ''));
$edad = filter_var($_POST['edad'] ?? null, FILTER_VALIDATE_INT);
$celular = trim((string)($_POST['celular'] ?? ''));
$direccion = trim((string)($_POST['direccion'] ?? ''));
$email = strtolower(trim((string)($_POST['email'] ?? '')));
$password = (string)($_POST['password'] ?? '');
$confirm = (string)($_POST['confirm-password'] ?? '');
if ($nombre === '' || strlen($nombre) > 100 || $apellido === '' || strlen($apellido) > 100 ||
    $edad === false || $edad < 18 || $edad > 100 ||
    !preg_match('/^[0-9+() -]{7,20}$/', $celular) ||
    $direccion === '' || strlen($direccion) > 255 ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100 ||
    strlen($password) < 12 || strlen($password) > 128 || $password !== $confirm) {
    $_SESSION['error_message_registro'] = 'Revisa los datos y utiliza una contraseña de al menos 12 caracteres.';
    header('Location: registro.php', true, 303); exit;
}
require __DIR__ . '/conexion.php';
try {
    $stmt = $conn->prepare('INSERT INTO usuarios (nombre, apellido, edad, celular, direccion, email, password, rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $role = 'usuario';
    $stmt->bind_param('ssisssss', $nombre, $apellido, $edad, $celular, $direccion, $email, $hashed, $role);
    $stmt->execute();
    $_SESSION['success_message_login'] = 'Cuenta creada. Ya puedes iniciar sesión.';
    header('Location: login.php', true, 303);
} catch (mysqli_sql_exception $e) {
    error_log('Tambito signup failed: ' . $e->getCode());
    $_SESSION['error_message_registro'] = $e->getCode() === 1062 ? 'El correo ya está registrado.' : 'No se pudo crear la cuenta.';
    header('Location: registro.php', true, 303);
}
exit;
