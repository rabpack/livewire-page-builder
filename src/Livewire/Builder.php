<?php

namespace Elearnac\PageBuilder\Livewire;

use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Elearnac\PageBuilder\ComponentRegistry;
use Elearnac\PageBuilder\Models\BuilderPage;

#[Layout('page-builder::layouts.builder')]
class Builder extends Component
{
    public ?int $pageId = null;
    public string $title = '';
    public string $slug = '';
    public bool $status = true;
    public array $document = [];
    public array $pageSettings = [
        'background' => '#f8fafc',
        'text' => '#0f172a',
        'header' => true,
        'footer' => true,
    ];
    public ?int $selected = null;
    public string $device = 'desktop';
    public int $previewVersion = 0;

    public function mount($slug = null): void
    {
        if ($slug) {
            $modelClass = config('page-builder.model', BuilderPage::class);
            $page = $modelClass::where('slug', $slug)->firstOrFail();
            $this->pageId = $page->id;
            $this->title = (string) $page->title;
            $this->slug = (string) $page->slug;
            $this->status = (bool) $page->status;
            $documentColumn = config('page-builder.document_column', 'document');
            $this->document = (array) ($page->{$documentColumn} ?? []);
            $this->pageSettings = array_merge($this->pageSettings, (array) data_get($page, 'settings', []));
        }

        $this->selected = count($this->document) ? 0 : null;
        $this->persistDraft();
    }

    public function updated(): void
    {
        $this->persistDraft();
    }

    private function persistDraft(): void
    {
        session()->put('page-builder.draft', [
            'title' => $this->title,
            'slug' => $this->slug,
            'document' => $this->document,
            'settings' => $this->pageSettings,
        ]);
        $this->previewVersion++;
    }

    #[On('pb-drop')]
    public function handleDrop(string $type, int $index, string $parent = ''): void
    {
        $this->dropBlock($type, $index, $parent);
    }

    public function dropBlock(string $type, int $index, string $parent = ''): void
    {
        $definition = $this->registry()->get($type);
        if (! $definition) {
            return;
        }

        $block = [
            'type' => $type,
            'settings' => $this->registry()->defaults($type),
            'children' => [],
            'devices' => ['desktop' => true, 'tablet' => true, 'mobile' => true, 'wide' => true],
        ];

        $targetPath = $parent !== '' ? $parent : null;
        $children = $targetPath ? (array) data_get($this->document, $targetPath, []) : $this->document;
        array_splice($children, max(0, min($index, count($children))), 0, [$block]);

        if ($targetPath) {
            data_set($this->document, $targetPath, $children);
        } else {
            $this->document = $children;
        }

        $this->selected = count($this->document) - 1;
        $this->persistDraft();
    }

    public function registry(): ComponentRegistry
    {
        return app(ComponentRegistry::class);
    }

    public function changeTitle(string $value): void
    {
        $this->title = $value;
        if (! $this->slug || $this->pageId === null) {
            $this->slug = Str::slug($value, '-');
        }
    }

    public function addBlock(string $type): void
    {
        $definition = $this->registry()->get($type);
        if (! $definition) {
            return;
        }

        $this->document[] = [
            'type' => $type,
            'settings' => $this->registry()->defaults($type),
            'children' => [],
            'devices' => ['desktop' => true, 'tablet' => true, 'mobile' => true, 'wide' => true],
        ];
        $this->selected = count($this->document) - 1;
        $this->persistDraft();
    }

    public function selectBlock(int $index): void
    {
        $this->selected = $index;
    }

    public function removeBlock(int $index): void
    {
        unset($this->document[$index]);
        $this->document = array_values($this->document);
        $this->selected = count($this->document) ? max(0, $this->selected - 1) : null;
        $this->persistDraft();
    }

    public function moveBlock(int $index, int $direction): void
    {
        $swap = $index + $direction;
        if (! isset($this->document[$index], $this->document[$swap])) {
            return;
        }

        [$this->document[$index], $this->document[$swap]] = [$this->document[$swap], $this->document[$index]];
        $this->selected = $swap;
        $this->persistDraft();
    }

    public function toggleDevice(int $index, string $device): void
    {
        $current = (bool) data_get($this->document, "{$index}.devices.{$device}", true);
        $this->document[$index]['devices'][$device] = ! $current;
        $this->persistDraft();
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'document' => 'array',
            'pageSettings.background' => 'nullable|string|max:20',
            'pageSettings.text' => 'nullable|string|max:20',
        ]);

        $modelClass = config('page-builder.model', BuilderPage::class);
        $documentColumn = config('page-builder.document_column', 'document');

        $attributes = [
            'title' => $this->title,
            'slug' => $this->slug,
            'status' => $this->status,
            $documentColumn => $this->document,
        ];

        if (is_subclass_of($modelClass, BuilderPage::class)) {
            $attributes['settings'] = $this->pageSettings;
        }

        $page = $this->pageId
            ? $modelClass::findOrFail($this->pageId)
            : new $modelClass();

        foreach (config('page-builder.model_defaults', []) as $key => $value) {
            if (! $page->exists || ! $page->{$key}) {
                $attributes[$key] = match ($value) {
                    '{auth_id}' => auth()->id(),
                    '{now}' => now(),
                    default => $value,
                };
            }
        }

        $page->fill($attributes)->save();
        $this->pageId = $page->id;

        Toaster::success('صفحه با موفقیت ذخیره شد.');
    }

    public function back(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route(config('page-builder.back_route', 'panel.page.index'));
    }

    public function render()
    {
        return view('page-builder::builder.builder', [
            'components' => $this->registry()->all(),
            'categories' => $this->registry()->categories(),
        ]);
    }
}
