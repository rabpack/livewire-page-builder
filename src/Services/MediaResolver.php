<?php

namespace Elearnac\PageBuilder\Services;

use Illuminate\Support\Facades\Storage;

final class MediaResolver
{
    public function url(mixed $id): ?string
    {
        if (empty($id)) {
            return null;
        }

        if (function_exists('filePayload')) {
            $url = filePayload($id)['url'] ?? null;
            if ($url) {
                return (string) $url;
            }
        }

        $model = config('page-builder.file_model');
        if (! $model || ! class_exists($model)) {
            return null;
        }

        $file = $model::find($id);
        if (! $file) {
            return null;
        }

        $payload = is_array($file->payload ?? null) ? $file->payload : [];
        if (! empty($payload['url'])) {
            return (string) $payload['url'];
        }

        return ! empty($file->path) ? Storage::disk('public')->url($file->path) : null;
    }

    public function urls(mixed $ids): array
    {
        $ids = is_array($ids) ? $ids : array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $ids) ?: [])));

        return array_values(array_filter(array_map(fn ($id) => $this->url($id), $ids)));
    }
}
