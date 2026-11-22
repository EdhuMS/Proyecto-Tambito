<?php use Tambito\Core\View; ?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="<?= View::escape($csrf ?? '') ?>">
<title>Administración · Tambito Demo</title>
<link rel="stylesheet" href="css/styles.css">
<link rel="stylesheet" href="css/modules/admin.css">
<script src="js/admin-security.js"></script>
<script src="js/guardarNosotros.js" defer></script>
<script src="js/guardar_categorias.js" defer></script>
<script src="js/admin-productos.js" defer></script>
<script src="js/actualizar_productos.js" defer></script>
<script src="js/guardarInicio.js" defer></script>
<script src="js/guardarContacto.js" defer></script>
</head>
<body class="body-admin">
  <a class="skip-link" href="#admin-main">Saltar al contenido</a>
  <?php require $pageFile; ?>
</body>
</html>
