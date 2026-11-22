<?php

namespace Elearnac\PageBuilder\Livewire\Components;

use Elearnac\PageBuilder\ComponentRegistry;
use Elearnac\PageBuilder\Services\DynamicData;
use Elearnac\PageBuilder\Services\MediaResolver;
use Elearnac\PageBuilder\Services\StyleCompiler;
use Illuminate\Support\Collection;

class UniversalBlock extends BlockComponent
{
    public string $type = '';
    public string $view = '';

    public function mount(array $settings = [], array $children = [], bool $editable = false, string $path = '', string $type = '', string $view = ''): void
    {
        parent::mount($settings, $children, $editable, $path);
        $this->type = $type;
        $this->view = $view;
    }

    public function render()
    {
        $definition = app(ComponentRegistry::class)->get($this->type) ?: [];
        $view = 'page-builder::components.universal.'.$this->view;
        if (! view()->exists($view)) {
            $view = 'page-builder::components.universal.text';
        }

        $source = (string) ($this->settings['source'] ?? data_get($definition, 'source', 'static'));
        $items = app(DynamicData::class)->items($source, $this->settings);
        $images = app(MediaResolver::class)->urls($this->settings['images'] ?? []);
        $rawImage = $this->setting('image');
        $image = is_array($rawImage) || is_bool($rawImage) ? null : app(MediaResolver::class)->url($rawImage);
        $variant = (string) ($this->settings['variant'] ?? data_get($definition, 'variant', $this->type));
        $style = app(StyleCompiler::class)->variables($this->settings);
        $classes = app(StyleCompiler::class)->classes($this->settings);

        return view($view, [
            'definition' => $definition,
            'type' => $this->type,
            'items' => $items,
            'images' => $images,
            'image' => $image,
            'variant' => $variant,
            'style' => $style,
            'classes' => $classes,
        ])
            ->with([
                'settings' => $this->settings,
                'children' => $this->children,
                'editable' => $this->editable,
                'path' => $this->path,
                'setting' => fn (string $key, mixed $default = null) => $this->setting($key, $default),
                'items' => $items instanceof Collection ? $items : collect($items),
            ]);
    }
}
