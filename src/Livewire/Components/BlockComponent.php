<?php

namespace Elearnac\PageBuilder\Livewire\Components;

use Elearnac\PageBuilder\Services\ResponsiveSettings;
use Livewire\Component;

abstract class BlockComponent extends Component
{
    public array $settings = [];
    public array $children = [];
    public bool $editable = false;
    public string $path = '';
    public ?string $selectedPath = null;
    public string $device = 'desktop';
    public int $previewVersion = 0;

    public function mount(
        array $settings = [],
        array $children = [],
        bool $editable = false,
        string $path = '',
        ?string $selectedPath = null,
        string $device = 'desktop',
        int $previewVersion = 0
    ): void {
        $this->settings = $settings;
        $this->children = $children;
        $this->editable = $editable;
        $this->path = $path;
        $this->selectedPath = $selectedPath;
        $this->device = $device;
        $this->previewVersion = $previewVersion;
    }

    public function setting(string $key, mixed $default = null): mixed
    {
        return $this->responsive()->value($this->settings, $key, $default, $this->device);
    }

    public function responsiveValue(string $device, string $key, mixed $default = null): mixed
    {
        return $this->responsive()->value($this->settings, $key, $default, $device);
    }

    private function responsive(): ResponsiveSettings
    {
        return app(ResponsiveSettings::class);
    }
}
