<?php
declare(strict_types=1);

namespace Tambito\Controllers\Api;

use Tambito\Models\ContentRepository;

final class ContentController
{
    public function show(string $section, bool $wrapped = false): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        $data = (new ContentRepository())->read($section);
        if ($wrapped) {
            $data = ['success' => true, 'data' => $data];
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
