<?php
use Tambito\Core\View;
$id = (string)($id ?? $name ?? 'field');
$name = (string)($name ?? $id);
$type = (string)($type ?? 'text');
$required = (bool)($required ?? true);
?>
<div class="input-group">
  <label for="<?= View::escape($id) ?>"><?= View::escape($label ?? $name) ?></label>
  <input id="<?= View::escape($id) ?>" type="<?= View::escape($type) ?>" name="<?= View::escape($name) ?>"
         autocomplete="<?= View::escape($autocomplete ?? 'off') ?>"
         placeholder="<?= View::escape($placeholder ?? '') ?>"
         <?= $required ? 'required' : '' ?>
         <?= isset($min) ? 'min="' . View::escape($min) . '"' : '' ?>
         <?= isset($max) ? 'max="' . View::escape($max) . '"' : '' ?>
         <?= isset($minlength) ? 'minlength="' . View::escape($minlength) . '"' : '' ?>>
</div>
