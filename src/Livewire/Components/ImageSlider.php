<?php

namespace Elearnac\PageBuilder\Livewire\Components;

use Elearnac\PageBuilder\Services\MediaResolver;

class ImageSlider extends BlockComponent
{
    public string $uid = '';

    public function mount(array $settings = [], array $children = [], bool $editable = false, string $path = ''): void
    {
        parent::mount($settings, $children, $editable, $path);
        $this->uid = 'pb-slider-'.uniqid();
    }

    public function images(): array
    {
        return app(MediaResolver::class)->urls($this->setting('images', []));
    }

    public function config(): array
    {
        return [
            'effect' => (string) $this->setting('effect', 'slide'),
            'loop' => (bool) $this->setting('loop', true),
            'autoplay' => (bool) $this->setting('autoplay', true),
            'speed' => (int) $this->setting('speed', 700),
            'navigation' => (bool) $this->setting('arrows', true),
            'pagination' => (bool) $this->setting('pagination', true),
            'breakpoints' => [
                0 => ['slidesPerView' => max(1, (int) $this->setting('slides_mobile', 1))],
                640 => ['slidesPerView' => max(1, (int) $this->setting('slides_tablet', 2))],
                1024 => ['slidesPerView' => max(1, (int) $this->setting('slides_desktop', 3))],
            ],
        ];
    }

    public function render()
    {
        return view('page-builder::components.image-slider', [
            'images' => $this->images(),
            'config' => $this->config(),
        ]);
    }
}
