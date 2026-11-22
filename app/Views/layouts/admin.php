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
<script src="js/admin/api.js"></script>
<script src="js/admin/about.js" defer></script>
<script src="js/admin/categories.js" defer></script>
<script src="js/admin/products-create.js" defer></script>
<script src="js/admin/products-edit.js" defer></script>
<script src="js/admin/home.js" defer></script>
<script src="js/admin/contact.js" defer></script>
</head>
<body class="body-admin">
  <a class="skip-link" href="#admin-main">Saltar al contenido</a>
  <?php require $pageFile; ?>
</body>
</html>
