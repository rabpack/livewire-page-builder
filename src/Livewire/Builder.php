<?php

namespace Elearnac\PageBuilder\Livewire;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Elearnac\PageBuilder\ComponentRegistry;
use Elearnac\PageBuilder\Models\BuilderPage;
use Elearnac\PageBuilder\Services\DocumentTree;
use Elearnac\PageBuilder\Services\FieldPresentation;
use Elearnac\PageBuilder\Services\FontRegistry;

#[Layout('page-builder::layouts.builder')]
class Builder extends Component
{
    private const DEVICES = ['wide', 'desktop', 'tablet', 'mobile'];

    public ?int $pageId = null;
    public string $pageTitle = '';
    public string $pageSlug = '';
    public ?string $editUrl = null;
    public ?string $publicUrl = null;
    public array $document = [];
    public array $pageSettings = [
        'font' => 'site',
        'background' => '#f8fafc',
        'background_dark' => '#020617',
        'text' => '#0f172a',
        'text_dark' => '#e2e8f0',
        'accent' => '#18b7a5',
        'radius' => 12,
        'container_width' => 1200,
        'header' => false,
        'footer' => false,
    ];
    public ?string $selected = null;
    public string $activeParent = '';
    public string $fileKey = 'images';
    public string $device = 'desktop';
    public string $designDevice = 'desktop';
    public ?string $savedAt = null;
    public int $previewVersion = 0;
    public array $history = [];
    public int $historyIndex = -1;
    public array $clipboard = [];
    public string $importText = '';
    public string $exportText = '';

    public function mount(string $slug): void
    {
        $modelClass = config('page-builder.model', BuilderPage::class);
        $page = $modelClass::where('slug', $slug)->firstOrFail();

        $this->pageId = $page->id;
        $this->pageTitle = (string) $page->title;
        $this->pageSlug = (string) $page->slug;
        $documentColumn = config('page-builder.document_column', 'document');
        $this->document = $this->tree()->prune((array) ($page->{$documentColumn} ?? []));
        $this->pageSettings = array_merge($this->pageSettings, (array) data_get($page, 'settings', []));

        $editRoute = (string) config('page-builder.edit_route', 'panel.page.edit');
        $this->editUrl = Route::has($editRoute) ? route($editRoute, $this->pageSlug) : null;

        $viewRoute = (string) config('page-builder.view_route', 'page.index');
        $this->publicUrl = Route::has($viewRoute) ? route($viewRoute, $this->pageSlug) : null;

        $this->selected = count($this->document) ? '0' : null;
        $this->activeParent = '';
    }

    public function updated(string $name): void
    {
        $this->savedAt = null;

        if (str_starts_with($name, 'pageSettings.')) {
            $rules = $this->pageSettingRules();
            $settingKey = str_contains($name, '.responsive.')
                ? substr($name, (int) strrpos($name, '.') + 1)
                : substr($name, strlen('pageSettings.'));
            $rule = $rules['pageSettings.'.$settingKey] ?? null;

            if (! $rule) {
                return;
            }

            try {
                $this->validateOnly($name, [$name => $rule]);
            } catch (ValidationException) {
                return;
            }
        }

        $this->persistPage();
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
        $this->selected = count($this->document) ? '0' : null;
        $this->activeParent = '';
        $this->persistPage();
    }

    public function redo(): void
    {
        if ($this->historyIndex >= count($this->history) - 1) {
            return;
        }
        $this->historyIndex++;
        $this->document = $this->history[$this->historyIndex];
        $this->selected = count($this->document) ? '0' : null;
        $this->activeParent = '';
        $this->persistPage();
    }

    private function persistPage(): void
    {
        if (! $this->pageId) {
            return;
        }

        $modelClass = config('page-builder.model', BuilderPage::class);
        $page = $modelClass::findOrFail($this->pageId);
        $attributes = [config('page-builder.document_column', 'document') => $this->document];
        $settingsColumn = config('page-builder.settings_column', 'settings');

        if (Schema::hasColumn($page->getTable(), $settingsColumn)) {
            $attributes[$settingsColumn] = $this->pageSettings;
        }

        $page->fill($attributes)->save();
        $this->savedAt = now()->format('H:i');
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

    public function tree(): DocumentTree
    {
        return app(DocumentTree::class);
    }

    public function dropBlock(string $type, int $index, string $parent = ''): void
    {
        $this->pushHistory();
        if (str_starts_with($type, 'move:')) {
            $this->moveBlockTo(substr($type, 5), $parent, $index);
            return;
        }

        $block = $this->makeBlock($type);
        if (! $block) {
            return;
        }

        $this->selected = $this->tree()->insert($this->document, $parent, $index, $block);
        $this->activeParent = $this->canHaveChildren($type) ? $this->selected : $parent;
        $this->syncSelection();
        $this->persistPage();
    }

    public function setFileKey(string $key): void
    {
        $this->fileKey = preg_replace('/[^a-zA-Z0-9_]/', '', $key) ?: 'images';
    }

    public function setDevice(string $device): void
    {
        if (! in_array($device, self::DEVICES, true)) {
            return;
        }

        $this->device = $device;
        $this->setDesignDevice($device);
    }

    public function setDesignDevice(string $device): void
    {
        if (! in_array($device, self::DEVICES, true)) {
            return;
        }

        $this->designDevice = $device;
        if ($device === 'desktop') {
            return;
        }

        if ($this->selected) {
            $type = (string) data_get($this->document, $this->selected.'.type');
            $fields = array_keys($this->registry()->get($type)['fields'] ?? []);
            foreach ($fields as $key) {
                $base = $this->selected.'.settings.'.$key;
                $override = $this->selected.'.settings.responsive.'.$device.'.'.$key;
                if (! Arr::has($this->document, $override)) {
                    data_set($this->document, $override, data_get($this->document, $base));
                }
            }
        }

        foreach (array_keys($this->pageSettings) as $key) {
            if ($key === 'responsive') {
                continue;
            }
            $override = 'responsive.'.$device.'.'.$key;
            if (! Arr::has($this->pageSettings, $override)) {
                data_set($this->pageSettings, $override, $this->pageSettings[$key]);
            }
        }

        $this->persistPage();
    }

    public function setAddTarget(string $path): void
    {
        $this->activeParent = $this->tree()->get($this->document, $path) && $this->canHaveChildren((string) data_get($this->document, $path.'.type')) ? $path : '';
    }

    public function clearAddTarget(): void
    {
        $this->activeParent = '';
    }

    public function canHaveChildren(string $type): bool
    {
        return (string) data_get($this->registry()->get($type), 'view') === 'layout';
    }

    #[On('pb-file')]
    public function addFile(array $data): void
    {
        $payload = $data['payload'] ?? $data;
        $id = $payload['id'] ?? null;

        if (! is_numeric($id) || $this->selected === null || ! $this->tree()->get($this->document, $this->selected)) {
            return;
        }

        $id = (int) $id;
        $path = $this->selected.'.settings.'.$this->fileKey;
        $images = (array) $this->tree()->value($this->document, $path, []);
        if (! in_array($id, $images, true)) {
            $images[] = $id;
        }
        $this->tree()->setValue($this->document, $path, array_values($images));
        $this->persistPage();
    }

    public function removeImage(int $index, ?string $key = null): void
    {
        if ($this->selected === null) {
            return;
        }
        if ($key !== null) {
            $this->setFileKey($key);
        }
        $path = $this->selected.'.settings.'.$this->fileKey;
        $images = (array) $this->tree()->value($this->document, $path, []);
        unset($images[$index]);
        $this->tree()->setValue($this->document, $path, array_values($images));
        $this->persistPage();
    }

    public function addRepeaterItem(string $key): void
    {
        if ($this->selected === null) {
            return;
        }
        $type = (string) $this->tree()->value($this->document, $this->selected.'.type');
        $field = data_get($this->registry()->get($type), 'fields.'.$key, []);
        $item = [];
        foreach ((array) data_get($field, 'subfields', []) as $subfield => $fieldType) {
            $item[$subfield] = $fieldType === 'number' ? 0 : '';
        }
        $path = $this->selected.'.settings.'.$key;
        $items = (array) $this->tree()->value($this->document, $path, []);
        $items[] = $item;
        $this->tree()->setValue($this->document, $path, $items);
        $this->persistPage();
    }

    public function removeRepeaterItem(string $key, int $index): void
    {
        if ($this->selected === null) {
            return;
        }
        $path = $this->selected.'.settings.'.$key;
        $items = (array) $this->tree()->value($this->document, $path, []);
        unset($items[$index]);
        $this->tree()->setValue($this->document, $path, array_values($items));
        $this->persistPage();
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

    public function addBlock(string $type, ?string $parent = null): void
    {
        $this->pushHistory();
        $block = $this->makeBlock($type);
        if (! $block) {
            return;
        }

        $parent ??= $this->activeParent;
        $this->selected = $this->tree()->insert($this->document, $parent, PHP_INT_MAX, $block);
        $this->activeParent = $this->canHaveChildren($type) ? $this->selected : $parent;
        $this->syncSelection();
        $this->persistPage();
    }

    public function selectBlock(string $path): void
    {
        if (! $this->tree()->get($this->document, $path)) {
            return;
        }

        $this->selected = $path;
        $type = (string) $this->tree()->value($this->document, $path.'.type');
        $this->activeParent = $this->canHaveChildren($type) ? $path : (string) ($this->tree()->parent($path) ?? '');
        $this->syncSelection();
    }

    private function syncSelection(): void
    {
        $type = $this->selected === null ? '' : (string) $this->tree()->value($this->document, $this->selected.'.type');
        $this->fileKey = $this->firstFileField($type);
    }

    private function firstFileField(string $type): string
    {
        foreach ((array) data_get($this->registry()->get($type), 'fields', []) as $key => $field) {
            if (($field['type'] ?? '') === 'files') {
                return $key;
            }
        }

        return 'images';
    }

    public function removeBlock(string $path): void
    {
        $this->pushHistory();
        $removed = $this->tree()->remove($this->document, $path);
        if (! $removed) {
            return;
        }

        $parent = (string) $removed['parent'];
        $this->selected = $this->tree()->siblingAfterRemoval(
            $parent,
            $removed['index'],
            $this->tree()->countChildren($this->document, $parent)
        );
        $this->activeParent = $parent;
        $this->syncSelection();
        $this->persistPage();
    }

    public function moveBlock(string $path, int $direction): void
    {
        $this->pushHistory();
        $moved = $this->tree()->swap($this->document, $path, $direction);
        if ($moved === null) {
            return;
        }
        $this->selected = $moved;
        $this->persistPage();
    }

    public function duplicateBlock(string $path): void
    {
        $this->pushHistory();
        $copy = $this->tree()->duplicate($this->document, $path);
        if ($copy === null) {
            return;
        }
        $this->selected = $copy;
        $this->syncSelection();
        $this->persistPage();
    }

    public function moveBlockTo(string $from, string $parent, int $index): void
    {
        $moved = $this->tree()->move($this->document, $from, $parent, $index);
        if ($moved === null) {
            return;
        }
        $this->selected = $moved;
        $this->activeParent = $parent;
        $this->syncSelection();
        $this->persistPage();
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

        $this->selected = count($this->document) ? '0' : null;
        $this->activeParent = '';
        $this->resetErrorBag();
        $this->persistPage();
    }

    public function copyBlock(): void
    {
        if ($this->selected === null) {
            return;
        }
        $this->clipboard = $this->tree()->get($this->document, $this->selected) ?? [];
    }

    public function pasteBlock(): void
    {
        if (! $this->clipboard || ! isset($this->clipboard['type'])) {
            $this->addError('clipboard', 'ابتدا یک المان را کپی کنید.');
            return;
        }

        $this->pushHistory();
        $parent = $this->activeParent;
        if ($this->selected !== null) {
            $parent = (string) ($this->tree()->parent($this->selected) ?? '');
        }
        $copy = json_decode(json_encode($this->clipboard), true);
        $index = $this->selected === null ? PHP_INT_MAX : $this->tree()->index($this->selected) + 1;
        $this->selected = $this->tree()->insert($this->document, $parent, $index, $copy);
        $this->resetErrorBag();
        $this->persistPage();
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
        $this->selected = count($blocks) ? '0' : null;
        $this->activeParent = '';
        $this->importText = '';
        $this->resetErrorBag();
        $this->persistPage();
    }

    public function clearPage(): void
    {
        $this->pushHistory();
        $this->document = [];
        $this->selected = null;
        $this->activeParent = '';
        $this->persistPage();
    }

    public function toggleDevice(string $path, string $device): void
    {
        $current = (bool) $this->tree()->value($this->document, $path.'.devices.'.$device, true);
        $this->tree()->setValue($this->document, $path.'.devices.'.$device, ! $current);
        $this->persistPage();
    }

    public function selectedTrail(): array
    {
        $trail = [['path' => '', 'label' => 'صفحه']];
        $segments = $this->selected === null ? [] : explode('.children.', $this->selected);
        $path = '';

        foreach ($segments as $segment) {
            $path = $path === '' ? $segment : $path.'.children.'.$segment;
            $type = (string) $this->tree()->value($this->document, $path.'.type');
            $trail[] = [
                'path' => $path,
                'label' => (string) data_get($this->registry()->get($type), 'label', $type),
            ];
        }

        return $trail;
    }

    public function activeParentLabel(): string
    {
        if ($this->activeParent === '') {
            return 'صفحه';
        }

        $type = (string) $this->tree()->value($this->document, $this->activeParent.'.type');

        return (string) data_get($this->registry()->get($type), 'label', $type);
    }

    private function pageSettingRules(): array
    {
        $color = ['nullable', 'string', 'max:40', 'regex:/^(#[0-9a-fA-F]{3,8}|rgba?\([0-9.,\s%]+\))$/'];

        return [
            'pageSettings.font' => ['nullable', Rule::in(array_keys(app(FontRegistry::class)->options()))],
            'pageSettings.background' => $color,
            'pageSettings.background_dark' => $color,
            'pageSettings.text' => $color,
            'pageSettings.text_dark' => $color,
            'pageSettings.accent' => $color,
            'pageSettings.radius' => 'nullable|numeric|min:0|max:120',
            'pageSettings.container_width' => 'nullable|numeric|min:320|max:2560',
            'pageSettings.header' => 'nullable|boolean',
            'pageSettings.footer' => 'nullable|boolean',
        ];
    }

    public function save(): void
    {
        $this->validate($this->pageSettingRules());
        $this->persistPage();
        Toaster::success('تغییرات صفحه با موفقیت ذخیره شد.');
    }

    public function back()
    {
        $editRoute = (string) config('page-builder.edit_route', 'panel.page.edit');

        if ($this->pageId && Route::has($editRoute)) {
            return redirect()->route($editRoute, $this->pageSlug);
        }

        return redirect()->route(config('page-builder.back_route', 'panel.page.index'));
    }

    public function render()
    {
        return view('page-builder::builder.builder', [
            'components' => $this->registry()->all(),
            'groups' => $this->registry()->groups(),
            'categories' => $this->registry()->categories(),
            'fonts' => app(FontRegistry::class)->options(),
            'fieldPresentation' => app(FieldPresentation::class),
            'selectionTrail' => $this->selectedTrail(),
            'activeParentLabel' => $this->activeParentLabel(),
            'designDevice' => $this->designDevice,
        ]);
    }
}
