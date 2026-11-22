<?php
declare(strict_types=1);

namespace Tambito\Models;

/**
 * Editable JSON documents. Mutations are authorized by the API controller.
 * Preserve existing JSON shape for the public storefront and admin forms.
 */
final class EditableContentRepository
{
    private const ALLOWED = ['categorias', 'inicio', 'nosotros', 'contacto'];

    public function read(string $document): array
    {
        $this->assertAllowed($document);
        return \tambito_read_json($this->path($document));
    }

    public function save(string $document, array $data): void
    {
        $this->assertAllowed($document);
        \tambito_write_json($this->path($document), $data);
    }

    private function path(string $name): string
    {
        return dirname(__DIR__, 2) . '/data/' . $name . '.json';
    }

    private function assertAllowed(string $name): void
    {
        if (!in_array($name, self::ALLOWED, true)) {
            throw new \InvalidArgumentException('Documento no autorizado.');
        }
    }
}
