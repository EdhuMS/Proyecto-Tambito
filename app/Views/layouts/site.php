<?php
use Tambito\Core\View;
?><!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Tambito: demostración académica de tienda web. Catálogo, carrito local y arquitectura MVC en PHP. No se realizan ventas reales.">
  <meta name="color-scheme" content="light">
  <title><?= View::escape($title ?? 'Tambito') ?> · Tambito Demo</title>
  <link rel="stylesheet" href="css/styles.css">
  <script src="js/cart.js" defer></script>
  <script src="js/ui.js" defer></script>
</head>
<body>
  <a class="skip-link" href="#main-content">Saltar al contenido</a>
  <?php View::partial('site-header'); ?>
  <main id="main-content" class="page-shell">
    <?php require $pageFile; ?>
  </main>
  <?php View::partial('site-footer'); ?>
</body>
</html>
