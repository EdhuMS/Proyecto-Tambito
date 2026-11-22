<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/app/bootstrap.php';
(new \Tambito\Controllers\Api\AdminApiController())->dispatch('saveContact');
