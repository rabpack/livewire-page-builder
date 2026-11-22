<?php
namespace Elearnac\PageBuilder\Livewire\Components;
use Illuminate\Support\Facades\Schema;
class CourseSlider extends BlockComponent {
    public string $uid = '';
    public function mount(array $settings = []){ parent::mount($settings); $this->uid = 'pb-courses-'.uniqid(); }
    public function items(): array {
        $model = config('page-builder.catalog.item_model');
        if(!$model || !class_exists($model)) return [];
        $query = $model::query();
        $categoryId = $this->setting('category_id');
        $key = config('page-builder.catalog.category_key','category_id');
        if($categoryId && Schema::hasColumn((new $model)->getTable(),$key)) $query->where($key,$categoryId);
        return $query->limit((int)$this->setting('limit',8))->get()
            ->map(fn($item)=>[
                'title'=>(string)data_get($item,config('page-builder.catalog.item_label','title'),''),
                'image'=>null,
            ])->all();
    }
    public function render(){ return view('page-builder::components.course-slider', ['items'=>$this->items()]); }
}
