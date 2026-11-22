<?php
namespace Elearnac\PageBuilder\Livewire\Components;
class Reviews extends BlockComponent {
    public function items(): array {
        $model = config('page-builder.reviews.model');
        if(!$model || !class_exists($model)) return [];
        return $model::query()->limit((int)$this->setting('limit',6))->get()
            ->map(fn($item)=>[
                'title'=>(string)data_get($item,config('page-builder.reviews.label','title'),'نظر'),
                'body'=>(string)data_get($item,'description',data_get($item,'body','')),
            ])->all();
    }
    public function render(){ return view('page-builder::components.reviews', ['items'=>$this->items()]); }
}
