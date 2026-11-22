<?php
// Password reset intentionally disabled until a secure recovery channel exists.
http_response_code(503);
header('Cache-Control: no-store');
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Recuperación de acceso | Tambito</title>
<link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="login-container" style="margin:5rem auto;padding:2rem">
<h1>Recuperación de acceso no disponible</h1>
<p>Esta es una demostración educativa. El antiguo método de recuperación por correo y edad se deshabilitó por seguridad.</p>
<p>Si administras una instalación local de pruebas, restablece el acceso desde MySQL de forma controlada y configura posteriormente un sistema de recuperación mediante tokens de un solo uso y un canal verificado.</p>
<a href="login.php">Volver al inicio de sesión</a>
</main>
</body>
</html>