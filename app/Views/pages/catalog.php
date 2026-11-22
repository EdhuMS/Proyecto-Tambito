<?php use Tambito\Core\View; ?>
<section class="page-intro container">
  <span class="eyebrow">DESCUBRE</span>
  <h1>Catálogo de demostración</h1>
  <p>Explora productos ilustrativos y prueba el carrito local. Precios y disponibilidad no comerciales.</p>
  <form method="get" action="categorias.php" class="catalog-filters" role="search">
    <label for="search">Buscar productos</label>
    <div class="filters-row">
      <input id="search" type="search" name="q" value="<?= View::escape($query) ?>" placeholder="Busca por producto o código…" maxlength="100">
      <label class="sr-only" for="category-filter">Filtrar por categoría</label>
      <select id="category-filter" name="categoria">
        <option value="">Todas las categorías</option>
        <?php foreach ($categories as $option): ?>
        <option value="<?= View::escape($option) ?>" <?= $category === $option ? 'selected' : '' ?>><?= View::escape($option) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-primary" type="submit">Buscar</button>
    </div>
  </form>
</section>
<div class="container catalog-content">
  <?php if ($groups === []): ?>
    <div class="empty-state">
      <strong>No encontramos productos</strong>
      <p>Prueba otro término o elimina los filtros.</p>
      <a href="categorias.php" class="btn btn-outline">Limpiar filtros</a>
    </div>
  <?php else: ?>
    <?php foreach ($groups as $group => $products): ?>
      <section class="section catalog-group" aria-label="<?= View::escape($group) ?>">
        <?php View::partial('section-heading', ['eyebrow' => 'CATEGORÍA', 'heading' => $group]); ?>
        <div class="product-grid">
          <?php foreach ($products as $product): ?>
            <?php View::partial('product-card', ['category' => $group, 'product' => $product]); ?>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
