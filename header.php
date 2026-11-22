<!-- Shared navigation; this is an unofficial educational demonstration. -->
<div class="demo-notice" role="note">Proyecto académico de demostración · No afiliado ni respaldado por Tambo+ · Sin compras ni pagos reales</div>
    <header class="header">
        <nav class="navbar">
            <div class="logo">
                <a href="index.php">
                    <img src="img/logo.svg" alt="Logo">
                </a>
            </div>

            <input type="checkbox" id="menu-toggle" class="menu-toggle">
            <label for="menu-toggle" class="menu-icon">
                <i class="fas fa-bars"></i>
            </label>

            <div class="nav-links">
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="categorias.php">Categorías</a></li>
                    <li><a href="nosotros.php">Nosotros</a></li>
                    <li><a href="contacto.php">Contacto</a></li>
                </ul>
            </div>

            <div class="nav-right">
                <form class="search-container" action="categorias.php" method="GET" role="search">
                  <label class="visually-hidden" for="search-products">Buscar en el catálogo</label>
                  <input id="search-products" type="search" name="q" maxlength="100" placeholder="Buscar productos...">
                  <button type="submit" aria-label="Buscar productos"><i class="fas fa-search"></i></button>
                </form>

                <div class="cart-container">
                    <a class="cart-icon" href="carrito.php" aria-label="Ver carrito de demostración">
                      <i class="fas fa-shopping-cart"></i> <span class="cart-count" aria-live="polite">0</span>
                    </a>
                </div>
                <div class="login-icon">
                    <a href="login.php" class="login-icon">
                        <i class="fas fa-user"></i>
                    </a>
                </div>
            </div>
        </nav>
    </header>
<script src="js/cart.js" defer></script>
