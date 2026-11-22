<?php

namespace Elearnac\PageBuilder;

final class ComponentRegistry
{
    public function all(): array
    {
        return config('page-builder.components', []);
    }

    public function get(string $type): ?array
    {
        return $this->all()[$type] ?? null;
    }

    public function defaults(string $type): array
    {
        $component = $this->get($type);

        if (! $component) {
            return [];
        }

        $defaults = [];
        foreach ($component['fields'] ?? [] as $key => $field) {
            $defaults[$key] = $field['default'] ?? '';
        }

        return $defaults;
    }

    public function classes(string $group = 'container'): string
    {
        $framework = config('page-builder.framework', 'tailwind');

        return config("page-builder.framework_classes.{$framework}.{$group}", '');
    }

    public function groups(): array
    {
        $groups = [];
        foreach ($this->all() as $type => $component) {
            $group = $component['group'] ?? 'عمومی';
            $groups[$group][$type] = $component;
        }
        return $groups;
    }

    public function categories(): array
    {
        $model = config('page-builder.catalog.category_model');
        if (! $model || ! class_exists($model)) {
            return [];
        }

        $label = config('page-builder.catalog.category_label', 'title');

        return $model::query()->limit(200)->pluck($label, 'id')->all();
    }
}
