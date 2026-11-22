<?php use Tambito\Core\View; ?>
<div class="demo-bar" role="note">
  <span class="container">Proyecto académico no oficial · No afiliado a Tambo+ · Sin compras ni pagos reales</span>
</div>
<header class="site-header" data-site-header>
  <div class="container site-nav">
    <a class="brand" href="index.php" aria-label="Tambito, ir al inicio">
      <img class="brand-logo" src="img/branding/tambito-logo-transparent.png" width="174" height="62" alt="Tambito+" decoding="async">
    </a>
    <button class="nav-toggle" type="button" data-menu-toggle aria-controls="nav-menu" aria-expanded="false" aria-label="Abrir menú de navegación">
      <span aria-hidden="true">☰</span>
    </button>
    <nav class="site-links" id="nav-menu" aria-label="Menú principal">
      <a href="index.php" <?= basename((string)($_SERVER['SCRIPT_NAME'] ?? '')) === 'index.php' ? 'aria-current="page"' : '' ?>>Inicio</a>
      <a href="categorias.php" <?= basename((string)($_SERVER['SCRIPT_NAME'] ?? '')) === 'categorias.php' ? 'aria-current="page"' : '' ?>>Catálogo</a>
      <a href="nosotros.php" <?= basename((string)($_SERVER['SCRIPT_NAME'] ?? '')) === 'nosotros.php' ? 'aria-current="page"' : '' ?>>Nosotros</a>
      <a href="contacto.php" <?= basename((string)($_SERVER['SCRIPT_NAME'] ?? '')) === 'contacto.php' ? 'aria-current="page"' : '' ?>>Contacto</a>
    </nav>
    <div class="site-actions">
      <a class="icon-link" href="login.php" aria-label="Mi cuenta">Mi cuenta</a>
      <a class="cart-link" href="carrito.php" aria-label="Ir al carrito de demostración">Carrito <span class="cart-count" aria-live="polite">0</span></a>
    </div>
  </div>
</header>
