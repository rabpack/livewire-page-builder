<?php

namespace Elearnac\PageBuilder\Livewire\Components;

use Elearnac\PageBuilder\Services\MediaResolver;
use Illuminate\Support\Facades\Schema;

class CourseSlider extends BlockComponent
{
    public string $uid = '';

    public function mount(
        array $settings = [],
        array $children = [],
        bool $editable = false,
        string $path = '',
        ?string $selectedPath = null,
        string $device = 'desktop',
        int $previewVersion = 0
    ): void {
        parent::mount($settings, $children, $editable, $path, $selectedPath, $device, $previewVersion);
        $this->uid = 'pb-courses-'.uniqid();
    }

    public function items(): array
    {
        $model = config('page-builder.catalog.item_model');
        if (! $model || ! class_exists($model)) {
            return [];
        }

        $query = $model::query();
        $table = (new $model)->getTable();
        $categoryKey = config('page-builder.catalog.category_key', 'category_id');
        if ($this->setting('category_id') && Schema::hasColumn($table, $categoryKey)) {
            $query->where($categoryKey, $this->setting('category_id'));
        }
        if (Schema::hasColumn($table, 'status')) {
            $query->where('status', 1);
        }

        $resolver = app(MediaResolver::class);

        return $query->latest()->limit(max(1, (int) $this->setting('limit', 8)))->get()
            ->map(fn ($item) => [
                'title' => (string) data_get($item, config('page-builder.catalog.item_label', 'title'), ''),
                'image' => $resolver->url(data_get($item, config('page-builder.catalog.item_image', 'poster_id'))),
                'price' => data_get($item, 'price'),
                'rating' => data_get($item, 'rating'),
                'url' => method_exists($item, 'getKey') ? route('course.show', data_get($item, 'slug') ?: $item->getKey()) : '#',
            ])->all();
    }

    public function render()
    {
        return view('page-builder::components.course-slider', ['items' => $this->items()]);
    }
}
