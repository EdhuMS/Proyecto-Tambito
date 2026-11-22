<?php use Tambito\Core\View; ?>
<section class="hero">
  <div class="container hero-grid">
    <div class="hero-copy">
      <span class="eyebrow">TIENDA WEB · PROYECTO ACADÉMICO</span>
      <h1>Una experiencia de catálogo <em>simple y cercana.</em></h1>
      <p>Descubre cómo funciona una tienda web construida con PHP, componentes reutilizables y JavaScript. Todo el contenido es ilustrativo.</p>
      <div class="hero-actions">
        <a href="categorias.php" class="btn btn-primary">Explorar catálogo <span aria-hidden="true">↗</span></a>
        <a href="nosotros.php" class="btn btn-outline">Conoce el proyecto</a>
      </div>
      <p class="hero-disclaimer">Simulación educativa: no se venden productos ni se procesan pagos.</p>
    </div>
    <div class="hero-art" aria-hidden="true">
      <div class="hero-art-glow"></div>
      <div class="hero-art-card"><span>ESTILO CONVENIENCIA</span><strong>Tu tienda,<br>tu ritmo.</strong><span>Demo interactiva · 2026</span></div>
      <div class="hero-float hero-float-a">✦ Fresco</div>
      <div class="hero-float hero-float-b">✺ Práctico</div>
    </div>
  </div>
</section>
<section class="section container">
  <?php View::partial('section-heading', ['eyebrow' => 'EXPLORAR', 'heading' => 'Categorías destacadas', 'link' => 'categorias.php', 'linkLabel' => 'Ver catálogo']); ?>
  <div class="category-grid">
    <?php
    $featured = ['Comidas' => 'img/comidas.png', 'Bebidas' => 'img/bebidas.webp', 'Antojos' => 'img/antojos.png', 'Despensa' => 'img/despensa.webp', 'Helados' => 'img/helado.png'];
    foreach ($featured as $name => $image) {
        View::partial('category-card', compact('name', 'image'));
    }
    ?>
  </div>
</section>
<section class="section section-muted">
  <div class="container">
    <?php View::partial('section-heading', ['eyebrow' => 'CATÁLOGO', 'heading' => 'Una muestra de nuestros productos', 'link' => 'categorias.php', 'linkLabel' => 'Explorar todo']); ?>
    <div class="product-grid">
      <?php
      $count = 0;
      foreach ($groups as $category => $products) {
          foreach ($products as $product) {
              View::partial('product-card', compact('category', 'product'));
              if (++$count >= 8) break 2;
          }
      }
      ?>
    </div>
  </div>
</section>
<section class="section container">
  <div class="feature-strip">
    <div><strong>Diseño adaptable</strong><span>Escritorio, tableta y móvil</span></div>
    <div><strong>Carrito local</strong><span>Prueba sin compras reales</span></div>
    <div><strong>Arquitectura MVC</strong><span>Vistas y componentes compartidos</span></div>
  </div>
</section>
