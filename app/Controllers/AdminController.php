<?php
declare(strict_types=1);

namespace Tambito\Controllers;

use Tambito\Core\View;

final class AdminController
{
    public function index(): void
    {
        require_once dirname(__DIR__, 2) . '/security.php';
        \tambito_session();
        if (empty($_SESSION['id']) || ($_SESSION['rol'] ?? '') !== 'admin') {
            header('Location: login.php', true, 302);
            exit;
        }

        View::render('admin', [
            'title' => 'Administración',
            'csrf' => \tambito_csrf_token(),
        ]);
    }
}
