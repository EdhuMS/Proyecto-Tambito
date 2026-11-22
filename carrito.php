<?php
declare(strict_types=1);
// Backward-compatible route wrapper; the view is owned by the MVC controller.
require_once __DIR__ . '/app/bootstrap.php';
(new \Tambito\Controllers\StorefrontController())->cart();
