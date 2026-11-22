<?php
declare(strict_types=1);

namespace Tambito\Controllers;

use Tambito\Core\View;
use Tambito\Models\ContentRepository;

final class StorefrontController
{
    private ContentRepository $content;

    public function __construct()
    {
        $this->content = new ContentRepository();
    }

    public function home(): void
    {
        View::render('home', [
            'title' => 'Inicio',
            'hero' => $this->content->read('inicio'),
            'groups' => $this->content->products(),
        ]);
    }

    public function catalog(): void
    {
        $query = trim((string)($_GET['q'] ?? ''));
        $category = trim((string)($_GET['categoria'] ?? ''));
        $query = mb_substr($query, 0, 100, 'UTF-8');
        $groups = $this->content->products($category !== '' ? $category : null, $query);
        View::render('catalog', [
            'title' => 'Catálogo',
            'groups' => $groups,
            'query' => $query,
            'category' => $category,
            'categories' => array_keys($this->content->read('categorias')),
        ]);
    }

    public function about(): void
    {
        View::render('about', ['title' => 'Nosotros', 'sections' => $this->content->read('nosotros')]);
    }

    public function contact(): void
    {
        View::render('contact', ['title' => 'Contacto', 'contact' => $this->content->read('contacto')['contacto'] ?? []]);
    }

    public function cart(): void
    {
        View::render('cart', ['title' => 'Carrito de demostración']);
    }
}
