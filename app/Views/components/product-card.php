<?php
use Tambito\Core\View;
$encoded = rawurlencode(json_encode($product, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}');
?>
<article class="product-card" data-product="<?= View::escape($encoded) ?>">
  <div class="product-image">
    <img src="<?= View::escape($product['imagen_url']) ?>" alt="<?= View::escape($product['nombre']) ?>" loading="lazy" width="220" height="220">
  </div>
  <div class="product-body">
    <span class="product-category"><?= View::escape($category ?? '') ?></span>
    <h3 class="product-name"><?= View::escape($product['nombre']) ?></h3>
    <div class="product-footer">
      <span class="product-price">S/ <?= number_format((float)$product['precio'], 2) ?></span>
      <button class="btn btn-icon add-to-cart" type="button" aria-label="Añadir <?= View::escape($product['nombre']) ?> al carrito">Añadir +</button>
    </div>
  </div>
</article>
