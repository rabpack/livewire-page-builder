<?php

namespace Elearnac\PageBuilder\Livewire;

use Livewire\Component;
use Elearnac\PageBuilder\ComponentRegistry;
use Elearnac\PageBuilder\Models\BuilderPage;
use Elearnac\PageBuilder\Services\DocumentTree;

class Renderer extends Component
{
    public string $slug;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    public function page()
    {
        $modelClass = config('page-builder.model', BuilderPage::class);
        $column = config('page-builder.document_column', 'document');

        return $modelClass::where('slug', $this->slug)->firstOrFail();
    }

    public function render()
    {
        $page = $this->page();
        $settings = (array) data_get($page, 'settings', []);
        $column = config('page-builder.document_column', 'document');
        $document = app(DocumentTree::class)->prune((array) ($page->{$column} ?? []));

        return view('page-builder::components.renderer', [
            'page' => $page,
            'blocks' => $document,
            'settings' => $settings,
            'registry' => app(ComponentRegistry::class),
        ]);
    }
}
