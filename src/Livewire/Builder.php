<?php

namespace Elearnac\PageBuilder\Livewire;

use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
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
    public array $history = [];
    public int $historyIndex = -1;
    public array $clipboard = [];
    public string $importText = '';
    public string $exportText = '';

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

        if (! $slug && ($draft = session('page-builder.draft'))) {
            $this->title = data_get($draft, 'title', '');
            $this->slug = data_get($draft, 'slug', '');
            $this->document = (array) data_get($draft, 'document', []);
            $this->pageSettings = array_merge($this->pageSettings, (array) data_get($draft, 'settings', []));
        }

        $this->selected = count($this->document) ? 0 : null;
        $this->persistDraft();
    }

    public function updated(): void
    {
        $this->persistDraft();
    }

    private function pushHistory(): void
    {
        $this->history = array_slice($this->history, 0, $this->historyIndex + 1);
        $this->history[] = $this->document;
        if (count($this->history) > 30) {
            array_shift($this->history);
        }
        $this->historyIndex = count($this->history) - 1;
    }

    public function undo(): void
    {
        if ($this->historyIndex <= 0) {
            return;
        }
        $this->historyIndex--;
        $this->document = $this->history[$this->historyIndex];
        $this->selected = count($this->document) ? 0 : null;
        $this->persistDraft();
    }

    public function redo(): void
    {
        if ($this->historyIndex >= count($this->history) - 1) {
            return;
        }
        $this->historyIndex++;
        $this->document = $this->history[$this->historyIndex];
        $this->selected = count($this->document) ? 0 : null;
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

    private function makeBlock(string $type, array $overrides = []): ?array
    {
        $definition = $this->registry()->get($type);

        if (! $definition) {
            return null;
        }

        return [
            'type' => $type,
            'settings' => array_replace_recursive($this->registry()->defaults($type), $overrides),
            'children' => [],
            'devices' => ['desktop' => true, 'tablet' => true, 'mobile' => true, 'wide' => true],
        ];
    }

    public function dropBlock(string $type, int $index, string $parent = ''): void
    {
        $this->pushHistory();
        if (str_starts_with($type, 'move:')) {
            $this->moveBlockTo((int) substr($type, 5), $index, $parent);
            return;
        }

        $block = $this->makeBlock($type);
        if (! $block) {
            return;
        }

        $targetPath = $parent !== '' ? $parent : null;
        $children = $targetPath ? (array) data_get($this->document, $targetPath, []) : $this->document;
        array_splice($children, max(0, min($index, count($children))), 0, [$block]);

        if ($targetPath) {
            data_set($this->document, $targetPath, $children);
        } else {
            $this->document = $children;
        }

        $this->selected = $parent !== '' ? count($this->document) - 1 : max(0, min($index, count($this->document) - 1));
        $this->persistDraft();
    }

    #[On('pb-file')]
    public function addFile(array $data): void
    {
        $id = $data['payload']['id'] ?? null;
        if ($id === null || $this->selected === null || ! isset($this->document[$this->selected])) {
            return;
        }
        $images = (array) data_get($this->document, "{$this->selected}.settings.images", []);
        if (! in_array($id, $images, true)) {
            $images[] = $id;
        }
        data_set($this->document, "{$this->selected}.settings.images", array_values($images));
        $this->persistDraft();
    }

    public function removeImage(int $index): void
    {
        if ($this->selected === null) {
            return;
        }
        $images = (array) data_get($this->document, "{$this->selected}.settings.images", []);
        unset($images[$index]);
        data_set($this->document, "{$this->selected}.settings.images", array_values($images));
        $this->persistDraft();
    }

    public function addRepeaterItem(string $key): void
    {
        if ($this->selected === null) {
            return;
        }
        $field = data_get($this->registry()->all(), "{$this->document[$this->selected]['type']}.fields.{$key}", []);
        $item = [];
        foreach ((array) data_get($field, 'subfields', []) as $subfield => $type) {
            $item[$subfield] = $type === 'number' ? 0 : '';
        }
        $items = (array) data_get($this->document, "{$this->selected}.settings.{$key}", []);
        $items[] = $item;
        data_set($this->document, "{$this->selected}.settings.{$key}", $items);
        $this->persistDraft();
    }

    public function removeRepeaterItem(string $key, int $index): void
    {
        if ($this->selected === null) {
            return;
        }
        $items = (array) data_get($this->document, "{$this->selected}.settings.{$key}", []);
        unset($items[$index]);
        data_set($this->document, "{$this->selected}.settings.{$key}", array_values($items));
        $this->persistDraft();
    }

    public function addFormField(): void
    {
        $this->addRepeaterItem('fields');
    }

    public function removeFormField(int $index): void
    {
        $this->removeRepeaterItem('fields', $index);
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
        $this->pushHistory();
        $block = $this->makeBlock($type);
        if (! $block) {
            return;
        }

        $this->document[] = $block;
        $this->selected = count($this->document) - 1;
        $this->persistDraft();
    }

    public function selectBlock(int $index): void
    {
        $this->selected = $index;
    }

    public function removeBlock(int $index): void
    {
        $this->pushHistory();
        unset($this->document[$index]);
        $this->document = array_values($this->document);
        $this->selected = count($this->document) ? max(0, $this->selected - 1) : null;
        $this->persistDraft();
    }

    public function moveBlock(int $index, int $direction): void
    {
        $this->pushHistory();
        $swap = $index + $direction;
        if (! isset($this->document[$index], $this->document[$swap])) {
            return;
        }

        [$this->document[$index], $this->document[$swap]] = [$this->document[$swap], $this->document[$index]];
        $this->selected = $swap;
        $this->persistDraft();
    }

    public function duplicateBlock(int $index): void
    {
        $this->pushHistory();
        if (! isset($this->document[$index])) {
            return;
        }
        $copy = $this->document[$index];
        array_splice($this->document, $index + 1, 0, [$copy]);
        $this->selected = $index + 1;
        $this->persistDraft();
    }

    public function moveBlockTo(int $from, int $to, string $parent = ''): void
    {
        $this->pushHistory();
        if ($from === $to) {
            return;
        }
        $targetPath = $parent !== '' ? $parent : null;
        $children = $targetPath ? (array) data_get($this->document, $targetPath, []) : $this->document;
        if (! isset($children[$from])) {
            return;
        }
        $block = $children[$from];
        unset($children[$from]);
        $children = array_values($children);
        array_splice($children, max(0, min($to, count($children))), 0, [$block]);
        if ($targetPath) {
            data_set($this->document, $targetPath, $children);
        } else {
            $this->document = $children;
        }
        $this->selected = $to;
        $this->persistDraft();
    }

    public function applyTemplate(string $key): void
    {
        $template = config("page-builder.templates.{$key}");

        if (! is_array($template)) {
            $this->addError('template', 'الگوی درخواستی یافت نشد.');
            return;
        }

        $this->pushHistory();
        $this->document = [];

        foreach ((array) data_get($template, 'blocks', []) as $definition) {
            $block = $this->makeBlock((string) data_get($definition, 'type', ''), (array) data_get($definition, 'settings', []));
            if ($block) {
                $this->document[] = $block;
            }
        }

        $this->selected = count($this->document) ? 0 : null;
        $this->resetErrorBag();
        $this->persistDraft();
    }

    public function copyBlock(): void
    {
        if ($this->selected === null || ! isset($this->document[$this->selected])) {
            return;
        }

        $this->clipboard = $this->document[$this->selected];
    }

    public function pasteBlock(): void
    {
        if (! $this->clipboard || ! isset($this->clipboard['type'])) {
            $this->addError('clipboard', 'ابتدا یک المان را کپی کنید.');
            return;
        }

        $this->pushHistory();
        $copy = json_decode(json_encode($this->clipboard), true);
        $position = ($this->selected ?? -1) + 1;
        array_splice($this->document, $position, 0, [$copy]);
        $this->selected = $position;
        $this->resetErrorBag();
        $this->persistDraft();
    }

    public function prepareExport(): void
    {
        $this->exportText = (string) json_encode([
            'settings' => $this->pageSettings,
            'document' => $this->document,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function importDocument(): void
    {
        $payload = json_decode($this->importText, true);

        if (! is_array($payload) || ! is_array(data_get($payload, 'document')) || count(data_get($payload, 'document')) > 100) {
            $this->addError('importText', 'ساختار فایل معتبر نیست.');
            return;
        }

        $blocks = [];
        foreach (data_get($payload, 'document') as $definition) {
            if (! is_array($definition) || ! $this->registry()->get((string) data_get($definition, 'type', ''))) {
                $this->addError('importText', 'یکی از المانهای فایل شناخته شده نیست.');
                return;
            }
            $block = $this->makeBlock((string) $definition['type'], (array) data_get($definition, 'settings', []));
            if ($block) {
                $blocks[] = $block;
            }
        }

        $this->pushHistory();
        $this->document = $blocks;
        $this->pageSettings = array_merge($this->pageSettings, (array) data_get($payload, 'settings', []));
        $this->selected = count($blocks) ? 0 : null;
        $this->importText = '';
        $this->resetErrorBag();
        $this->persistDraft();
    }

    public function setPageSetting(string $key, mixed $value): void
    {
        $this->pageSettings[$key] = $value;
        $this->persistDraft();
    }

    public function clearDraft(): void
    {
        session()->forget('page-builder.draft');
        $this->title = '';
        $this->slug = '';
        $this->document = [];
        $this->selected = null;
        $this->pageId = null;
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
            'groups' => $this->registry()->groups(),
            'categories' => $this->registry()->categories(),
        ]);
    }
}
