<div class="flex h-screen w-full overflow-hidden bg-slate-100 text-slate-800 dark:bg-slate-950 dark:text-slate-100"
     dir="rtl"
     x-data="{ device: @js($device), panelTab: 'add', settingsTab: 'content', toolSearch: '' }"
     @pb-drop.window="$wire.call('dropBlock', $event.detail.type, $event.detail.index, $event.detail.parent); panelTab='settings'">

    {{-- Right sidebar --}}
    <aside class="flex h-full w-[336px] shrink-0 flex-col border-e border-slate-200 bg-white shadow-xl dark:border-slate-800 dark:bg-slate-900">

        {{-- Tabs --}}
        <div class="flex items-center gap-1 border-b border-slate-200 p-2 dark:border-slate-800">
            <button type="button" @click="panelTab='add'" :class="panelTab==='add' ? 'bg-brand-500 text-white' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800'"
                    class="flex-1 rounded-lg px-3 py-2 text-xs font-extrabold transition-colors">+ المانها</button>
            <button type="button" @click="panelTab='settings'" :class="panelTab==='settings' ? 'bg-brand-500 text-white' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800'"
                    class="flex-1 rounded-lg px-3 py-2 text-xs font-extrabold transition-colors">تنظیمات</button>
            <button type="button" @click="panelTab='page'" :class="panelTab==='page' ? 'bg-brand-500 text-white' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800'"
                    class="flex-1 rounded-lg px-3 py-2 text-xs font-extrabold transition-colors">صفحه</button>
            <button type="button" @click="toggleTheme()" class="grid size-9 place-items-center rounded-lg border border-slate-200 text-slate-500 hover:border-brand-400 dark:border-slate-700" aria-label="تغییر پوسته">
                <i class="far fa-moon text-xs" x-show="!darkMode"></i>
                <i class="far fa-sun text-xs" x-show="darkMode" x-cloak></i>
            </button>
        </div>

        {{-- Add elements --}}
        <div class="min-h-0 flex-1 overflow-y-auto" x-show="panelTab==='add'">
            <div class="p-3">
                <input type="search" x-model="toolSearch" placeholder="جستجوی المان..."
                       class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs dark:border-slate-700 dark:bg-slate-900">
            </div>
            @foreach ($groups as $group => $items)
                <div class="px-3 pb-4" x-data="{ open: true }">
                    <button type="button" @click="open=!open" class="mb-2 flex w-full items-center justify-between text-xs font-extrabold text-slate-700 dark:text-slate-200">
                        <span>{{ $group }}</span><i class="far fa-chevron-up text-[10px]" :class="open ? '' : 'rotate-180'"></i>
                    </button>
                    <div class="grid grid-cols-2 gap-2" x-show="open">
                        @foreach ($items as $type => $component)
                            <button type="button" draggable="true"
                                    x-on:dragstart="$event.dataTransfer.setData('text/plain', '{{ $type }}'); $event.dataTransfer.effectAllowed='copy'"
                                    x-show="'{{ $component['label'] }}'.includes(toolSearch) || '{{ $type }}'.includes(toolSearch)"
                                    class="flex cursor-grab flex-col items-center gap-2 rounded-xl border border-slate-200 p-3 text-center transition-colors hover:border-brand-400 hover:bg-brand-50 active:cursor-grabbing dark:border-slate-700 dark:hover:bg-brand-500/10">
                                <i class="{{ $component['icon'] }} text-lg text-brand-500"></i>
                                <span class="text-[11px] font-bold text-slate-700 dark:text-slate-200">{{ $component['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Element / page settings --}}
        <div class="min-h-0 flex-1 overflow-y-auto p-4" x-show="panelTab==='settings'">
            @if ($selected !== null && isset($document[$selected]))
                @php
                    $definition = $components[$document[$selected]['type']] ?? ['fields' => []];
                    $fieldGroups = ['content' => [], 'style' => [], 'advanced' => []];
                    foreach ($definition['fields'] ?? [] as $fieldKey => $field) {
                        $section = $field['section'] ?? (in_array($field['type'], ['color', 'number']) ? 'style' : 'content');
                        $fieldGroups[$section][$fieldKey] = $field;
                    }
                @endphp
                <div class="mb-3 rounded-lg bg-slate-100 p-2 text-xs font-extrabold dark:bg-slate-800">
                    {{ $definition['label'] ?? $document[$selected]['type'] }}
                </div>
                <div class="mb-3 flex gap-1 rounded-lg bg-slate-100 p-1 dark:bg-slate-800">
                    @foreach (['content' => 'محتوا', 'style' => 'استایل', 'advanced' => 'پیشرفته'] as $key => $label)
                        <button type="button" @click="settingsTab='{{ $key }}'" :class="settingsTab==='{{ $key }}' ? 'bg-white text-brand-600 shadow dark:bg-slate-900' : 'text-slate-500'"
                                class="flex-1 rounded-md px-2 py-1.5 text-[11px] font-bold">{{ $label }}</button>
                    @endforeach
                </div>

                @foreach ($fieldGroups as $section => $fields)
                    <div x-show="settingsTab==='{{ $section }}'" class="space-y-3">
                        @forelse ($fields as $key => $field)
                            <div>
                                <label class="mb-1 block text-[11px] font-bold text-slate-600 dark:text-slate-300">{{ $field['label'] }}</label>
                                @switch($field['type'])
                                    @case('switch')
                                        <input type="checkbox" wire:model.live="document.{{ $selected }}.settings.{{ $key }}" class="rounded border-slate-300 text-brand-500">
                                        @break
                                    @case('select')
                                    @case('model')
                                        <select wire:model.live="document.{{ $selected }}.settings.{{ $key }}" class="w-full rounded-lg border border-slate-200 px-2 py-1.5 text-xs dark:border-slate-700 dark:bg-slate-900">
                                            @if ($field['type'] === 'model')<option value="">همه</option>@endif
                                            @foreach (($field['type'] === 'model' ? $categories : ($field['options'] ?? [])) as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @break
                                    @case('textarea')
                                        <textarea rows="3" wire:model.live.debounce.300ms="document.{{ $selected }}.settings.{{ $key }}" class="w-full rounded-lg border border-slate-200 px-2 py-1.5 text-xs dark:border-slate-700 dark:bg-slate-900"></textarea>
                                        @break
                                    @case('files')
                                        @php($images = (array) data_get($document, $selected.'.settings.'.$key, []))
                                        <div class="space-y-2">
                                            @foreach ($images as $imageIndex => $imageId)
                                                <div class="flex items-center gap-2 rounded-lg border border-slate-200 p-1 dark:border-slate-700">
                                                    <img src="{{ app(\Elearnac\PageBuilder\Services\MediaResolver::class)->url($imageId) }}" class="h-10 w-14 rounded object-cover" alt="">
                                                    <span class="flex-1 truncate text-[10px] text-slate-500">#{{ $imageId }}</span>
                                                    <button type="button" wire:click="removeImage({{ $imageIndex }})" class="rounded bg-rose-600 px-2 py-1 text-[10px] text-white">×</button>
                                                </div>
                                            @endforeach
                                            @if (config('page-builder.file_manager.component'))
                                                <livewire:dynamic-component :is="config('page-builder.file_manager.component')"
                                                    :name="config('page-builder.file_manager.name')"
                                                    title="انتخاب تصویر از مدارک سایت"
                                                    :multipleSelect="true"
                                                    :fileTypes="['image/png','image/jpeg','image/webp','image/svg']" />
                                            @endif
                                        </div>
                                        @break
                                    @case('repeater')
                                        @php($repeater = (array) data_get($document, $selected.'.settings.'.$key, []))
                                        <div class="space-y-2">
                                            @foreach ($repeater as $fieldIndex => $formField)
                                                <div class="rounded-lg border border-slate-200 p-2 dark:border-slate-700">
                                                    <div class="flex justify-end"><button type="button" wire:click="removeFormField({{ $fieldIndex }})" class="text-[10px] text-rose-600">حذف فیلد</button></div>
                                                    <input type="text" wire:model.live.debounce.300ms="document.{{ $selected }}.settings.{{ $key }}.{{ $fieldIndex }}.name" placeholder="نام فیلد" class="mb-1 w-full rounded border border-slate-200 px-2 py-1 text-[11px] dark:border-slate-700 dark:bg-slate-900">
                                                    <input type="text" wire:model.live.debounce.300ms="document.{{ $selected }}.settings.{{ $key }}.{{ $fieldIndex }}.label" placeholder="برچسب" class="mb-1 w-full rounded border border-slate-200 px-2 py-1 text-[11px] dark:border-slate-700 dark:bg-slate-900">
                                                    <select wire:model.live="document.{{ $selected }}.settings.{{ $key }}.{{ $fieldIndex }}.type" class="mb-1 w-full rounded border border-slate-200 px-2 py-1 text-[11px] dark:border-slate-700 dark:bg-slate-900">
                                                        @foreach (['text'=>'متن','email'=>'ایمیل','textarea'=>'پیام','number'=>'عدد','tel'=>'تلفن'] as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach
                                                    </select>
                                                    <label class="flex items-center gap-2 text-[10px] text-slate-500"><input type="checkbox" wire:model.live="document.{{ $selected }}.settings.{{ $key }}.{{ $fieldIndex }}.required"> الزامی</label>
                                                </div>
                                            @endforeach
                                            <button type="button" wire:click="addFormField" class="w-full rounded-lg border border-dashed border-brand-400 py-2 text-[11px] font-bold text-brand-600">+ افزودن فیلد</button>
                                        </div>
                                        @break
                                    @case('color')
                                        <input type="color" wire:model.live="document.{{ $selected }}.settings.{{ $key }}" class="h-9 w-full rounded border border-slate-200">
                                        @break
                                    @default
                                        <input type="text" wire:model.live.debounce.300ms="document.{{ $selected }}.settings.{{ $key }}" class="w-full rounded-lg border border-slate-200 px-2 py-1.5 text-xs dark:border-slate-700 dark:bg-slate-900">
                                @endswitch
                            </div>
                        @empty
                            <p class="text-xs text-slate-400">تنظیمهایی برای این بخش وجود ندارد.</p>
                        @endforelse
                    </div>
                @endforeach
            @else
                <p class="text-xs text-slate-400">یک المان را انتخاب کنید تا تنظیمات آن نمایش داده شود.</p>
            @endif
        </div>

        {{-- Page settings --}}
        <div class="min-h-0 flex-1 overflow-y-auto p-4" x-show="panelTab==='page'">
            <div class="mb-4 space-y-3">
                <div><label class="mb-1 block text-[11px] font-bold">پسزمینه صفحه</label><input type="color" wire:model.live="pageSettings.background" class="h-9 w-full rounded border border-slate-200"></div>
                <div><label class="mb-1 block text-[11px] font-bold">رنگ متن</label><input type="color" wire:model.live="pageSettings.text" class="h-9 w-full rounded border border-slate-200"></div>
                <label class="flex items-center justify-between text-xs font-bold"><span>نمایش هدر سایت</span><input type="checkbox" wire:model.live="pageSettings.header"></label>
                <label class="flex items-center justify-between text-xs font-bold"><span>نمایش فوتر سایت</span><input type="checkbox" wire:model.live="pageSettings.footer"></label>
                <button type="button" wire:click="clearDraft" class="w-full rounded-lg border border-rose-200 px-3 py-2 text-xs font-bold text-rose-600">پاک کردن پیشنویس</button>
            </div>
        </div>
    </aside>

    {{-- Preview --}}
    <div class="flex min-w-0 flex-1 flex-col">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center gap-2">
                <button type="button" wire:click="back" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 hover:border-brand-400 dark:border-slate-700 dark:text-slate-300">
                    <i class="far fa-arrow-right"></i> بازگشت
                </button>
                <input wire:model.live.debounce.300ms="title" wire:change="changeTitle($event.target.value)"
                       class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-bold dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                       placeholder="عنوان صفحه">
            </div>
            <div class="flex items-center gap-1 rounded-lg border border-slate-200 bg-slate-50 p-1 dark:border-slate-700 dark:bg-slate-900">
                @foreach (['wide' => 'عریض', 'desktop' => 'دسکتاپ', 'tablet' => 'تبلت', 'mobile' => 'موبایل'] as $key => $label)
                    <button type="button" wire:click="$set('device', '{{ $key }}')" x-on:click="device='{{ $key }}'"
                            class="rounded-md px-3 py-1.5 text-xs font-bold transition-colors {{ $device === $key ? 'bg-brand-500 text-white' : 'text-slate-500 hover:bg-white dark:hover:bg-slate-800' }}">{{ $label }}</button>
                @endforeach
            </div>
            <button type="button" wire:click="save" class="rounded-lg bg-brand-500 px-4 py-2 text-xs font-bold text-white hover:bg-brand-600">ذخیره صفحه</button>
        </div>

        <div class="min-h-0 flex-1 overflow-y-auto p-4">
            <div class="mx-auto w-full overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition-all duration-300 dark:border-slate-800"
                 :class="{'max-w-[390px]': device==='mobile','max-w-[768px]': device==='tablet','max-w-[1024px]': device==='desktop','max-w-none': device==='wide'}"
                 style="background: {{ $pageSettings['background'] ?? '#f8fafc' }}; color: {{ $pageSettings['text'] ?? '#0f172a' }};">

                @if ($pageSettings['header'] ?? true) @include(config('page-builder.preview_partials.header', 'general::layouts.partials.header')) @endif

                <div class="min-h-96 p-4">
                    <div class="my-1 h-2 rounded border border-dashed border-brand-300 bg-brand-50/40"
                         data-dropzone data-path=""
                         ondragover="event.preventDefault()"
                         ondrop="event.preventDefault(); window.dispatchEvent(new CustomEvent('pb-drop',{detail:{type:event.dataTransfer.getData('text/plain'),index:0,parent:''}}))"></div>

                    @forelse ($document as $index => $block)
                        <div class="group relative my-2 rounded-xl border-2 p-1 transition-colors {{ $selected === $index ? 'border-brand-500 bg-brand-50/40' : 'border-transparent hover:border-brand-200' }}"
                             draggable="true"
                             ondragstart="event.dataTransfer.setData('text/plain','move:{{ $index }}')"
                             wire:click="selectBlock({{ $index }})" wire:key="pb-block-{{ $index }}">
                            <div class="absolute start-2 top-2 z-10 flex gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                                <button type="button" wire:click.stop="moveBlock({{ $index }}, -1)" class="rounded bg-white px-2 py-1 text-[10px] shadow">↑</button>
                                <button type="button" wire:click.stop="moveBlock({{ $index }}, 1)" class="rounded bg-white px-2 py-1 text-[10px] shadow">↓</button>
                                <button type="button" wire:click.stop="duplicateBlock({{ $index }})" class="rounded bg-white px-2 py-1 text-[10px] shadow">کپی</button>
                                <button type="button" wire:click.stop="removeBlock({{ $index }})" class="rounded bg-rose-600 px-2 py-1 text-[10px] text-white shadow">حذف</button>
                            </div>
                            @livewire('page-builder.component.'.$block['type'], [
                                'settings' => $block['settings'],
                                'children' => $block['children'] ?? [],
                                'editable' => true,
                                'path' => (string) $index,
                            ], key('pb-'.$index.'-'.$block['type']))
                        </div>
                        <div class="my-1 h-2 rounded border border-dashed border-brand-300 bg-brand-50/40"
                             data-dropzone data-path=""
                             ondragover="event.preventDefault()"
                             ondrop="event.preventDefault(); window.dispatchEvent(new CustomEvent('pb-drop',{detail:{type:event.dataTransfer.getData('text/plain'),index:{{ $index + 1 }},parent:''}}))"></div>
                    @empty
                        <div class="my-6 rounded-xl border border-dashed border-slate-300 p-14 text-center text-sm text-slate-400" @click="panelTab='add'">
                            المانها را از پنل سمت راست بکشید و اینجا رها کنید.
                        </div>
                    @endforelse
                </div>

                @if ($pageSettings['footer'] ?? true) @include(config('page-builder.preview_partials.footer', 'general::layouts.partials.footer')) @endif
            </div>
        </div>
    </div>
</div>
