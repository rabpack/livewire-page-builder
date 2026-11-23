<?php

namespace Elearnac\PageBuilder\Livewire\Components;

use Elearnac\PageBuilder\ComponentRegistry;
use Elearnac\PageBuilder\Services\DynamicData;
use Elearnac\PageBuilder\Services\MediaResolver;
use Elearnac\PageBuilder\Services\ResponsiveSettings;
use Elearnac\PageBuilder\Services\StyleCompiler;
use Illuminate\Support\Collection;

class UniversalBlock extends BlockComponent
{
    public string $type = '';
    public string $view = '';

    public function mount(
        array $settings = [],
        array $children = [],
        bool $editable = false,
        string $path = '',
        ?string $selectedPath = null,
        string $device = 'desktop',
        int $previewVersion = 0,
        string $type = '',
        string $view = ''
    ): void {
        parent::mount($settings, $children, $editable, $path, $selectedPath, $device, $previewVersion);
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

        $responsive = app(ResponsiveSettings::class);
        $settings = $responsive->resolve($this->settings, $this->device);
        $source = (string) ($settings['source'] ?? data_get($definition, 'source', 'static'));
        $items = app(DynamicData::class)->items($source, $settings);
        $images = app(MediaResolver::class)->urls($settings['images'] ?? []);
        $rawImage = $this->setting('image');
        $image = is_array($rawImage) || is_bool($rawImage) ? null : app(MediaResolver::class)->url($rawImage);
        $variant = (string) ($settings['variant'] ?? data_get($definition, 'variant', $this->type));
        $style = app(StyleCompiler::class)->variables($this->settings, $this->device);
        $classes = app(StyleCompiler::class)->classes($settings);

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
                'settings' => $settings,
                'gridVariables' => $responsive->gridVariables($this->settings),
                'children' => $this->children,
                'editable' => $this->editable,
                'path' => $this->path,
                'selected' => $this->selectedPath,
                'device' => $this->device,
                'previewVersion' => $this->previewVersion,
                'components' => app(ComponentRegistry::class)->all(),
                'setting' => fn (string $key, mixed $default = null) => $this->setting($key, $default),
                'responsiveValue' => fn (string $forDevice, string $key, mixed $default = null) => $responsive->value($this->settings, $key, $default, $forDevice),
                'items' => $items instanceof Collection ? $items : collect($items),
            ]);
    }
}
