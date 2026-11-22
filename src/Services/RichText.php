<?php

namespace Elearnac\PageBuilder\Services;

final class RichText
{
    public function clean(mixed $value): string
    {
        $html = is_scalar($value) ? (string) $value : '';
        $sanitizer = config('page-builder.editor.sanitizer');

        if (is_string($sanitizer) && class_exists($sanitizer)
            && (method_exists($sanitizer, 'clean') || is_subclass_of($sanitizer, \Illuminate\Support\Facades\Facade::class))) {
            return (string) $sanitizer::clean($html);
        }

        return strip_tags($html);
    }
}
