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

    public function fontOptions(): array
    {
        return app(\Elearnac\PageBuilder\Services\FontRegistry::class)->options();
    }

    public function isUniversal(string $type): bool
    {
        $component = $this->get($type);

        return $component !== null && ($component['class'] ?? null) === \Elearnac\PageBuilder\Livewire\Components\UniversalBlock::class;
    }

    public function mountProps(string $type, array $props): array
    {
        $component = $this->get($type);
        if (! $component || ! $this->isUniversal($type)) {
            return $props;
        }

        $props['type'] = $type;
        $props['view'] = (string) ($component['view'] ?? 'text');

        return $props;
    }

    public function search(string $term): array
    {
        $term = trim(mb_strtolower($term));
        if ($term === '') {
            return $this->all();
        }

        return array_filter($this->all(), function (array $component, string $type) use ($term) {
            return str_contains(mb_strtolower((string) ($component['label'] ?? '')), $term)
                || str_contains(mb_strtolower($type), $term)
                || str_contains(mb_strtolower((string) ($component['group'] ?? '')), $term);
        }, ARRAY_FILTER_USE_BOTH);
    }
}
