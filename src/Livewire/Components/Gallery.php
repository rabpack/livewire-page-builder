<?php

namespace Elearnac\PageBuilder\Livewire\Components;

use Elearnac\PageBuilder\Services\MediaResolver;
use Illuminate\Support\Facades\Schema;

class Gallery extends BlockComponent
{
    public function items(): array
    {
        if ($this->setting('source', 'product') === 'manual') {
            return $this->manualItems();
        }

        $model = config('page-builder.gallery.item_model', config('page-builder.catalog.item_model'));
        if (! $model || ! class_exists($model)) {
            return [];
        }

        $query = $model::query();
        $table = (new $model)->getTable();
        $categoryKey = config('page-builder.gallery.category_key', 'category_id');
        if ($this->setting('category_id') && Schema::hasColumn($table, $categoryKey)) {
            $query->where($categoryKey, $this->setting('category_id'));
        }

        $order = $this->setting('order', 'latest');
        if ($order === 'title' && Schema::hasColumn($table, 'title')) {
            $query->orderBy('title');
        } else {
            $query->orderByDesc(Schema::hasColumn($table, 'created_at') ? 'created_at' : 'id');
        }

        $resolver = app(MediaResolver::class);

        return $query->limit(max(1, (int) $this->setting('limit', 8)))->get()
            ->map(function ($item) use ($resolver) {
                $ids = $this->imageIds($item);
                return [
                    'title' => (string) data_get($item, config('page-builder.gallery.item_label', 'title'), ''),
                    'url' => $resolver->urls($ids)[0] ?? null,
                ];
            })
            ->filter(fn ($item) => $item['url'] !== null)
            ->values()
            ->all();
    }

    private function imageIds(mixed $item): array
    {
        $fields = config('page-builder.gallery.item_images', ['poster_id', 'image_id', 'image']);
        foreach ((array) $fields as $field) {
            $value = data_get($item, $field);
            if ($value) {
                return is_array($value) ? $value : [$value];
            }
        }
        $images = data_get($item, 'images');
        if (is_array($images)) {
            return array_values(array_map(fn ($image) => is_array($image) ? ($image['id'] ?? null) : $image, $images));
        }
        return [];
    }

    private function manualItems(): array
    {
        return collect(app(MediaResolver::class)->urls($this->setting('images', [])))
            ->map(fn ($url) => ['title' => '', 'url' => $url])
            ->all();
    }

    public function render()
    {
        return view('page-builder::components.gallery', ['items' => $this->items()]);
    }
}
