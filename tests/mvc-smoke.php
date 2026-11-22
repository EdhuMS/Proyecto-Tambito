<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';

use Tambito\Controllers\StorefrontController;
use Tambito\Core\View;
use Tambito\Models\ContentRepository;

function verify(bool $ok, string $message): void
{
    if (!$ok) {
        fwrite(STDERR, "FAIL: {$message}\n");
        exit(1);
    }
}

$repository = new ContentRepository();
$groups = $repository->products();
verify(count($groups) === 5, 'catálogo de cinco categorías');
verify(array_sum(array_map('count', $groups)) === 40, 'catálogo de 40 productos');
verify(array_sum(array_map('count', $repository->products(null, 'producto'))) >= 5, 'búsqueda en modelo');
verify(count($repository->products('Despensa')) === 1, 'filtro por categoría');
verify($repository->products('../private') === [], 'categoría desconocida vacía');

$controller = new StorefrontController();
$routes = ['home', 'catalog', 'about', 'contact', 'cart'];
$_SERVER['SCRIPT_NAME'] = '/index.php';

foreach ($routes as $method) {
    $_GET = [];
    ob_start();
    $controller->$method();
    $html = ob_get_clean();
    verify(substr_count($html, '<html') === 1, "layout único para {$method}");
    verify(str_contains($html, 'id="main-content"'), "contenido principal para {$method}");
    verify(str_contains($html, 'demo-bar'), "aviso educativo para {$method}");
    verify(str_contains($html, 'css/styles.css'), "estilos compartidos para {$method}");
}

$_GET = ['q' => '<script>alert(1)</script>'];
ob_start();
$controller->catalog();
$html = ob_get_clean();
verify(!str_contains($html, '<script>alert(1)</script>'), 'salida escapada');
verify(str_contains($html, '&lt;script&gt;'), 'entrada visible y escapada');

verify(str_contains((string)file_get_contents(__DIR__ . '/../css/styles.css'), "core/tokens.css"), 'tokens como origen CSS');
verify(str_contains((string)file_get_contents(__DIR__ . '/../css/modules/responsive.css'), 'max-width:680px'), 'breakpoint móvil');
echo "OK: modelo, cinco páginas MVC, escape y módulos CSS\n";
