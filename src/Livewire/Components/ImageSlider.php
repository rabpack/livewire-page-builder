<?php
namespace Elearnac\PageBuilder\Livewire\Components;
class ImageSlider extends BlockComponent {
    public string $uid = '';
    public function mount(array $settings = []){ parent::mount($settings); $this->uid = 'pb-slider-'.uniqid(); }
    public function images(): array {
        return array_values(array_filter(array_map('trim', explode("\n", (string)$this->setting('images','')))));
    }
    public function render(){ return view('page-builder::components.image-slider', ['images'=>$this->images()]); }
}
