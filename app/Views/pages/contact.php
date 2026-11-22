<?php use Tambito\Core\View; ?>
<section class="page-intro container">
  <span class="eyebrow">CONTACTO</span>
  <h1>Este espacio es una demostración.</h1>
  <p>Los canales de atención comercial no están habilitados. Si deseas revisar el código, utiliza el repositorio del proyecto.</p>
</section>
<section class="section container contact-grid">
  <article class="info-card">
    <span class="eyebrow">INFORMACIÓN</span>
    <h2>Contacto ilustrativo</h2>
    <p><?= View::escape($contact['horario'] ?? 'Sin atención comercial') ?></p>
    <p>Teléfono: <?= View::escape($contact['telefono'] ?? 'No disponible') ?></p>
    <p>Correo: <?= View::escape($contact['email'] ?: 'No disponible') ?></p>
  </article>
  <article class="info-card info-card-highlight">
    <span class="eyebrow">CÓDIGO ABIERTO PARA APRENDIZAJE</span>
    <h2>Explora cómo está construido.</h2>
    <p>El proyecto implementa un esquema MVC incremental, componentes reutilizables y una interfaz adaptable.</p>
    <a href="https://github.com/EdhuMS/Proyecto-Tambito" class="btn btn-primary" rel="noopener noreferrer" target="_blank">Ver repositorio ↗</a>
  </article>
</section>
