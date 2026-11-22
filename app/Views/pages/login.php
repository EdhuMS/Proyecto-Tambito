<?php use Tambito\Core\View; ?>
<section class="section container">
  <div class="login-container">
    <div class="login-header">
      <span class="eyebrow">CUENTAS DE DEMOSTRACIÓN</span>
      <h1>Iniciar sesión</h1>
      <p>Accede a tu cuenta local de prueba.</p>
    </div>
    <?php if ($success !== ''): ?><div class="message-container success-message" role="status"><?= View::escape($success) ?></div><?php endif; ?>
    <?php if ($error !== ''): ?><div class="message-container error-message" role="alert"><?= View::escape($error) ?></div><?php endif; ?>
    <form class="login-form" action="validar_usuario.php" method="post">
      <input type="hidden" name="csrf_token" value="<?= View::escape($csrf) ?>">
      <?php View::partial('form-field', ['id' => 'email', 'name' => 'email', 'type' => 'email', 'label' => 'Correo electrónico', 'autocomplete' => 'email']); ?>
      <?php View::partial('form-field', ['id' => 'password', 'name' => 'password', 'type' => 'password', 'label' => 'Contraseña', 'autocomplete' => 'current-password']); ?>
      <button class="btn-login" type="submit">Ingresar</button>
      <div class="login-options"><a href="recuperar_contraseña_paso1.php" class="forgot-password">¿Olvidaste tu contraseña?</a></div>
    </form>
    <div class="login-footer"><p>¿No tienes una cuenta? <a class="register-link" href="registro.php">Regístrate</a></p></div>
  </div>
</section>
