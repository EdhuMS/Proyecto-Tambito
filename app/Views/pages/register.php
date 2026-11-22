<?php use Tambito\Core\View; ?>
<section class="section container">
  <div class="login-container">
    <div class="login-header">
      <span class="eyebrow">REGISTRO DE DEMOSTRACIÓN</span>
      <h1>Crea tu cuenta</h1>
      <p>Solo para ejecutar el proyecto en un entorno de pruebas.</p>
    </div>
    <?php if ($error !== ''): ?><div class="message-container error-message" role="alert"><?= View::escape($error) ?></div><?php endif; ?>
    <form class="login-form" action="guardar_usuario.php" method="post" data-register-form>
      <input type="hidden" name="csrf_token" value="<?= View::escape($csrf) ?>">
      <div class="form-row">
        <?php View::partial('form-field', ['name' => 'nombre', 'label' => 'Nombre', 'autocomplete' => 'given-name']); ?>
        <?php View::partial('form-field', ['name' => 'apellido', 'label' => 'Apellido', 'autocomplete' => 'family-name']); ?>
      </div>
      <div class="form-row">
        <?php View::partial('form-field', ['name' => 'edad', 'type' => 'number', 'label' => 'Edad', 'min' => 18, 'max' => 100]); ?>
        <?php View::partial('form-field', ['name' => 'celular', 'type' => 'tel', 'label' => 'Teléfono', 'autocomplete' => 'tel']); ?>
      </div>
      <?php View::partial('form-field', ['name' => 'direccion', 'label' => 'Dirección', 'autocomplete' => 'street-address']); ?>
      <?php View::partial('form-field', ['name' => 'email', 'type' => 'email', 'label' => 'Correo electrónico', 'autocomplete' => 'email']); ?>
      <?php View::partial('form-field', ['name' => 'password', 'type' => 'password', 'label' => 'Contraseña (mínimo 12 caracteres)', 'autocomplete' => 'new-password', 'minlength' => 12]); ?>
      <?php View::partial('form-field', ['name' => 'confirm-password', 'id' => 'confirm-password', 'type' => 'password', 'label' => 'Confirmar contraseña', 'autocomplete' => 'new-password', 'minlength' => 12]); ?>
      <p class="form-help" id="password-feedback" role="alert" aria-live="polite"></p>
      <button type="submit" class="btn-login">Crear cuenta</button>
    </form>
    <div class="login-footer"><p>¿Ya tienes cuenta? <a class="register-link" href="login.php">Inicia sesión</a></p></div>
  </div>
</section>
