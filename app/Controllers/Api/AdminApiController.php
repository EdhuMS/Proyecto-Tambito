<?php
declare(strict_types=1);

namespace Tambito\Controllers\Api;

use Tambito\Models\EditableContentRepository;

/** Secure JSON API actions; the public HTTP endpoint files are thin wrappers. */
final class AdminApiController
{
    private EditableContentRepository $content;

    public function __construct()
    {
        $this->content = new EditableContentRepository();
    }

    public function dispatch(string $action): void
    {
        require_once dirname(__DIR__, 3) . '/security.php';
        \tambito_admin_api();
        $allowed = [
            'createProduct', 'updateProduct', 'deleteProduct',
            'renameCategory', 'saveContact', 'saveAbout', 'saveHome',
        ];
        if (!in_array($action, $allowed, true)) {
            \tambito_fail('Operación no disponible.', 404);
        }
        $this->{$action}();
        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
    }

    private function field(string $name, int $max = 150): string
    {
        $value = trim((string)($_POST[$name] ?? ''));
        if (strlen($value) > $max) {
            \tambito_fail('Campo demasiado largo: ' . $name);
        }
        return $value;
    }

    private function code(): string
    {
        $value = $this->field('codigo', 30);
        if (!preg_match('/^[A-Za-z0-9_-]{2,30}$/', $value)) {
            \tambito_fail('Código de producto inválido.');
        }
        return $value;
    }

    private function price(): float
    {
        $number = filter_var($_POST['precio'] ?? null, FILTER_VALIDATE_FLOAT);
        if ($number === false || $number <= 0 || $number > 100000) {
            \tambito_fail('Precio inválido.');
        }
        return round((float)$number, 2);
    }

    private function image(string $field): ?string
    {
        return \tambito_upload_image($field, dirname(__DIR__, 3) . '/uploads', 'uploads');
    }

    private function createProduct(): void
    {
        $data = $this->content->read('categorias');
        $category = $this->field('categoria', 80);
        $code = $this->code();
        $name = $this->field('nombre');
        $price = $this->price();
        if ($category === '' || $name === '') {
            \tambito_fail('Nombre o categoría vacíos.');
        }
        foreach ($data as $group) {
            foreach (($group['productos'] ?? []) as $product) {
                if (($product['codigo'] ?? '') === $code) {
                    \tambito_fail('Ya existe un producto con ese código.', 409);
                }
            }
        }
        $image = $this->image('imagen');
        if ($image === null) {
            \tambito_fail('Selecciona una imagen JPG, PNG o WebP.');
        }
        if (!isset($data[$category])) {
            $data[$category] = ['productos' => []];
        }
        $data[$category]['productos'][] = [
            'codigo' => $code, 'nombre' => $name,
            'precio' => $price, 'imagen_url' => $image,
        ];
        $this->content->save('categorias', $data);
    }

    private function updateProduct(): void
    {
        $data = $this->content->read('categorias');
        $code = $this->code();
        $name = $this->field('nombre');
        $price = $this->price();
        if ($name === '') \tambito_fail('El nombre es obligatorio.');
        $found = false;
        foreach ($data as &$category) {
            if (!isset($category['productos']) || !is_array($category['productos'])) continue;
            foreach ($category['productos'] as &$product) {
                if (($product['codigo'] ?? '') !== $code) continue;
                $product['nombre'] = $name;
                $product['precio'] = $price;
                $image = $this->image('imagen');
                if ($image !== null) $product['imagen_url'] = $image;
                $found = true;
                break 2;
            }
            unset($product);
        }
        unset($category, $product);
        if (!$found) \tambito_fail('Producto no encontrado.', 404);
        $this->content->save('categorias', $data);
    }

    private function deleteProduct(): void
    {
        $data = $this->content->read('categorias');
        $code = $this->code();
        $found = false;
        foreach ($data as &$category) {
            $items = $category['productos'] ?? [];
            foreach ($items as $index => $item) {
                if (($item['codigo'] ?? '') !== $code) continue;
                array_splice($items, $index, 1);
                $category['productos'] = $items;
                $found = true;
                break 2;
            }
        }
        unset($category);
        if (!$found) \tambito_fail('Producto no encontrado.', 404);
        $this->content->save('categorias', $data);
        // Keep uploaded images: multiple products may reference one file.
    }

    private function renameCategory(): void
    {
        $data = $this->content->read('categorias');
        $old = $this->field('categoria_antigua', 80);
        $new = $this->field('categoria_nueva', 80);
        if ($new === '' || !isset($data[$old])) {
            \tambito_fail('Categoría no válida o inexistente.');
        }
        if ($old !== $new && isset($data[$new])) {
            \tambito_fail('La categoría ya existe.', 409);
        }
        if ($old !== $new) {
            $data[$new] = $data[$old];
            unset($data[$old]);
            $this->content->save('categorias', $data);
        }
    }

    private function saveContact(): void
    {
        $keys = [
            'telefono', 'email', 'instagram_url', 'instagram_text',
            'facebook_url', 'facebook_text', 'tiktok_url', 'tiktok_text', 'horario',
        ];
        $contact = [];
        foreach ($keys as $key) {
            $contact[$key] = $this->field('contacto_' . $key, 250);
        }
        if ($contact['email'] !== '' && !filter_var($contact['email'], FILTER_VALIDATE_EMAIL)) {
            \tambito_fail('Correo electrónico inválido.');
        }
        foreach (['instagram_url', 'facebook_url', 'tiktok_url'] as $key) {
            if ($contact[$key] !== '' &&
                (!filter_var($contact[$key], FILTER_VALIDATE_URL) ||
                 strtolower((string)parse_url($contact[$key], PHP_URL_SCHEME)) !== 'https')) {
                \tambito_fail('URL HTTPS inválida.');
            }
        }
        $this->content->save('contacto', ['contacto' => $contact]);
    }

    private function saveAbout(): void
    {
        $current = $this->content->read('nosotros');
        $new = [];
        foreach (['nosotros', 'mision', 'vision'] as $key) {
            $title = $this->field($key . '_titulo');
            $description = $this->field($key . '_descripcion', 4000);
            if ($title === '') \tambito_fail('El título es obligatorio.');
            $image = $this->image($key . '_imagen');
            $new[$key] = [
                'titulo' => $title,
                'descripcion' => $description,
                'imagen_url' => $image ?? ($current[$key]['imagen_url'] ?? 'img/default-icon.png'),
            ];
        }
        $this->content->save('nosotros', $new);
    }

    private function saveHome(): void
    {
        $current = $this->content->read('inicio');
        $slogan = $this->field('eslogan', 200);
        $featured = [];
        for ($i = 0; $i < 5; $i++) {
            $name = $this->field('categoria_' . $i . '_nombre', 100);
            $image = $this->image('categoria_' . $i . '_imagen');
            $featured[] = [
                'nombre' => $name,
                'imagen_url' => $image ?? ($current['categorias_destacadas'][$i]['imagen_url'] ?? ''),
            ];
        }
        $this->content->save('inicio', ['eslogan' => $slogan, 'categorias_destacadas' => $featured]);
    }
}
