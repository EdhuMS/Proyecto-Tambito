<?php
declare(strict_types=1);
// MVC-compatible entrypoint for the administrator panel.
require_once __DIR__ . '/app/bootstrap.php';
(new \Tambito\Controllers\AdminController())->index();
