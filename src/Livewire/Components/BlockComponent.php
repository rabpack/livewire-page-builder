<?php

namespace Elearnac\PageBuilder\Livewire\Components;

use Livewire\Component;

abstract class BlockComponent extends Component
{
    public array $settings = [];
    public array $children = [];
    public bool $editable = false;
    public string $path = '';

    public function mount(array $settings = [], array $children = [], bool $editable = false, string $path = ''): void
    {
        $this->settings = $settings;
        $this->children = $children;
        $this->editable = $editable;
        $this->path = $path;
    }

    public function setting(string $key, mixed $default = null): mixed
    {
        return $this->settings[$key] ?? $default;
    }
}
