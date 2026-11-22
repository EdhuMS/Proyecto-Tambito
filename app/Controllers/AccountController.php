<?php
declare(strict_types=1);

namespace Tambito\Controllers;

use Tambito\Core\View;

final class AccountController
{
    public function login(): void
    {
        require_once dirname(__DIR__, 2) . '/security.php';
        \tambito_session();
        $success = (string)($_SESSION['success_message_login'] ?? '');
        $error = (string)($_SESSION['error_message'] ?? '');
        unset($_SESSION['success_message_login'], $_SESSION['error_message']);
        View::render('login', [
            'title' => 'Iniciar sesión',
            'csrf' => \tambito_csrf_token(),
            'success' => $success,
            'error' => $error,
        ]);
    }

    public function register(): void
    {
        require_once dirname(__DIR__, 2) . '/security.php';
        \tambito_session();
        $error = (string)($_SESSION['error_message_registro'] ?? '');
        unset($_SESSION['error_message_registro']);
        View::render('register', [
            'title' => 'Crear cuenta',
            'csrf' => \tambito_csrf_token(),
            'error' => $error,
        ]);
    }
}
