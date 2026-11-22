<?php
namespace Elearnac\PageBuilder\Livewire\Components;
class Gallery extends BlockComponent {
    public function images(): array {
        return array_values(array_filter(array_map('trim', explode("\n",(string)$this->setting('images','')))));
    }
    public function render(){ return view('page-builder::components.gallery',['images'=>$this->images()]); }
}
