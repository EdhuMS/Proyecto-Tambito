<?php use Tambito\Core\View; ?>
<div class="section-heading">
  <div><span class="eyebrow"><?= View::escape($eyebrow ?? 'DESCUBRE') ?></span>
  <h2><?= View::escape($heading ?? '') ?></h2></div>
  <?php if (!empty($link)): ?><a class="text-link" href="<?= View::escape($link) ?>"><?= View::escape($linkLabel ?? 'Ver todo') ?> <span aria-hidden="true">↗</span></a><?php endif; ?>
</div>
