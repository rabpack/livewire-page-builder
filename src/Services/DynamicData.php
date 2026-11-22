<?php

namespace Elearnac\PageBuilder\Services;

use Illuminate\Support\Collection;

final class DynamicData
{
    public function __construct(private MediaResolver $media)
    {
    }

    public function items(string $source, array $settings): Collection
    {
        if ($source === '' || $source === 'static') {
            return $this->normalize((array) ($settings['items'] ?? []), $settings);
        }

        $definition = config("page-builder.data_sources.{$source}");
        if (! is_array($definition)) {
            return collect();
        }

        $model = data_get($definition, 'model');
        if (! $model || ! class_exists($model)) {
            return collect();
        }

        $query = $model::query();
        foreach ((array) data_get($definition, 'scopes', []) as $scope) {
            if (method_exists($query, $scope)) {
                $query->{$scope}();
            }
        }

        $category = data_get($settings, 'category_id');
        $categoryColumn = data_get($definition, 'category_key');
        if ($category !== '' && $category !== null && $categoryColumn) {
            $query->where($categoryColumn, $category);
        }

        if (data_get($settings, 'featured_only')) {
            $featuredColumn = data_get($definition, 'featured_key');
            if ($featuredColumn) {
                $query->where($featuredColumn, true);
            }
        }

        foreach (['order' => 'created_at', 'order_column' => 'id'] as $setting => $fallback) {
            $column = data_get($settings, $setting, $fallback);
            if (in_array($column, ['latest', 'oldest', 'title'], true)) {
                $column = $column === 'title' ? (data_get($definition, 'title', 'title')) : 'created_at';
                $query->orderBy($column, $column === 'title' ? 'asc' : ($column === 'created_at' && data_get($settings, 'order') === 'oldest' ? 'asc' : 'desc'));
                break;
            }
        }

        $limit = max(1, min(100, (int) data_get($settings, 'limit', 8)));
        $records = $query->limit($limit)->get();

        return $this->normalize($records->all(), $settings, $definition);
    }

    private function normalize(array $records, array $settings, array $definition = []): Collection
    {
        return collect($records)->map(function ($record) use ($settings, $definition) {
            if ($record instanceof \ArrayAccess || is_object($record)) {
                $item = [
                    'title' => (string) data_get($record, data_get($definition, 'title', 'title'), ''),
                    'text' => (string) data_get($record, data_get($definition, 'text', 'description'), ''),
                    'meta' => (string) data_get($record, data_get($definition, 'meta', ''), ''),
                    'image' => $this->media->url(data_get($record, data_get($definition, 'image', ''), null)),
                    'url' => $this->url($record, $definition),
                ];
            } else {
                $item = is_array($record) ? $record : [];
                $item['image'] = $this->media->url($item['image'] ?? null);
            }

            $item['image'] ??= null;
            $item['url'] ??= '#';
            $item['title'] ??= '';
            $item['text'] ??= '';
            $item['meta'] ??= '';

            return $item;
        })->filter(fn (array $item) => $item['title'] !== '' || $item['text'] !== '' || $item['image'] !== null)->values();
    }

    private function url(object $record, array $definition): string
    {
        $route = data_get($definition, 'route');
        if ($route && function_exists('route')) {
            try {
                return route($route, $record);
            } catch (\Throwable) {
                return '#';
            }
        }

        $attribute = data_get($definition, 'url');
        $url = $attribute ? data_get($record, $attribute) : null;

        return is_string($url) && $url !== '' ? $url : '#';
    }
}
