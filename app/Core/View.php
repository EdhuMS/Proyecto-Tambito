<?php
declare(strict_types=1);

namespace Tambito\Core;

final class View
{
    public static function render(string $page, array $data = []): void
    {
        $pages = ['home', 'catalog', 'about', 'contact', 'cart', 'admin', 'login', 'register'];
        if (!in_array($page, $pages, true)) {
            http_response_code(404);
            exit('Página no encontrada');
        }
        extract($data, EXTR_SKIP);
        $pageFile = dirname(__DIR__) . '/Views/pages/' . $page . '.php';
        require dirname(__DIR__) . '/Views/layouts/' . ($page === 'admin' ? 'admin' : 'site') . '.php';
    }

    public static function escape(mixed $text): string
    {
        return htmlspecialchars((string)$text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function partial(string $name, array $data = []): void
    {
        $allowed = ['site-header', 'site-footer', 'product-card', 'section-heading', 'category-card', 'form-field'];
        if (!in_array($name, $allowed, true)) {
            throw new \InvalidArgumentException('Componente desconocido');
        }
        extract($data, EXTR_SKIP);
        require dirname(__DIR__) . '/Views/components/' . $name . '.php';
    }
}
