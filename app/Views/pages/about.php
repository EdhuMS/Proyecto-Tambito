<?php use Tambito\Core\View; ?>
<section class="page-intro container">
  <span class="eyebrow">NUESTRO PROYECTO</span>
  <h1>Una tienda creada para aprender.</h1>
  <p>Tambito es una práctica de desarrollo web. No representa ni está asociada a una tienda comercial.</p>
</section>
<section class="section container about-grid">
  <?php
  $labels = ['nosotros' => 'El proyecto', 'mision' => 'Nuestro propósito', 'vision' => 'Hacia dónde vamos'];
  foreach ($labels as $key => $label):
      $details = $sections[$key] ?? [];
  ?>
    <article class="info-card">
      <span class="eyebrow"><?= View::escape(strtoupper($label)) ?></span>
      <h2><?= View::escape($details['titulo'] ?? $label) ?></h2>
      <p><?= View::escape($details['descripcion'] ?? 'Sección de demostración.') ?></p>
    </article>
  <?php endforeach; ?>
</section>
<section class="section section-muted">
  <div class="container feature-strip">
    <div><strong>PHP + MySQL</strong><span>Backend y autenticación local</span></div>
    <div><strong>JSON</strong><span>Contenido del catálogo</span></div>
    <div><strong>JavaScript</strong><span>Interactividad y carrito local</span></div>
  </div>
</section>
