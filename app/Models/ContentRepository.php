<?php
declare(strict_types=1);

namespace Tambito\Models;

final class ContentRepository
{
    public function __construct(private readonly string $base = __DIR__ . '/../../data') {}

    public function read(string $name): array
    {
        if (!in_array($name, ['inicio', 'nosotros', 'contacto', 'categorias'], true)) {
            return [];
        }
        $path = $this->base . '/' . $name . '.json';
        if (!is_file($path)) {
            return [];
        }
        $result = json_decode((string)file_get_contents($path), true);
        return is_array($result) ? $result : [];
    }

    public function products(?string $category = null, string $search = ''): array
    {
        $result = [];
        $search = trim($search);
        $lower = static fn (string $value): string => function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
        $search = $lower($search);
        foreach ($this->read('categorias') as $label => $group) {
            if (!is_string($label) || ($category !== null && $category !== $label)) {
                continue;
            }
            $items = [];
            foreach ($group['productos'] ?? [] as $item) {
                if (!is_array($item) || empty($item['codigo']) || empty($item['nombre'])) {
                    continue;
                }
                if ($search !== '' && !str_contains($lower($label . ' ' . $item['nombre'] . ' ' . $item['codigo']), $search)) {
                    continue;
                }
                $price = filter_var($item['precio'] ?? null, FILTER_VALIDATE_FLOAT);
                $path = (string)($item['imagen_url'] ?? '');
                if ($price === false || $price < 0 || !preg_match('~^(?:\./)?(?:img/PRODUCTOS-CATEGORIA/[A-Za-z0-9_-]+|uploads/[a-f0-9]{32})\.(?:png|jpg|webp)$~i', $path)) {
                    continue;
                }
                $items[] = [
                    'codigo' => (string)$item['codigo'],
                    'nombre' => (string)$item['nombre'],
                    'precio' => (float)$price,
                    'imagen_url' => $path,
                ];
            }
            if ($items !== []) {
                $result[$label] = $items;
            }
        }
        return $result;
    }
}
