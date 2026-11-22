<?php use Tambito\Core\View; ?>
<a class="category-card" href="categorias.php?categoria=<?= rawurlencode((string)($category ?? $name)) ?>">
  <span class="category-image">
    <img src="<?= View::escape($image) ?>" alt="" loading="lazy" width="220" height="160">
  </span>
  <span class="category-label"><?= View::escape($name) ?></span>
  <span class="category-arrow" aria-hidden="true">↗</span>
</a>
