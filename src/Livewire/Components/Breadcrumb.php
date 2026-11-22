<?php
namespace Elearnac\PageBuilder\Livewire\Components;
class Breadcrumb extends BlockComponent {
    public function trail(): array {
        $segments = array_filter(explode('/', trim(request()->getPathInfo(),'/')));
        $trail = [['title'=>$this->setting('home','خانه'),'url'=>url('/')]];
        $url = '';
        foreach($segments as $segment){ $url .= '/'.$segment; $trail[] = ['title'=>urldecode($segment),'url'=>url($url)]; }
        return $trail;
    }
    public function render(){ return view('page-builder::components.breadcrumb',['trail'=>$this->trail()]); }
}
