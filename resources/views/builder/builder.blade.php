<div class="flex h-screen w-full overflow-hidden bg-slate-100 dark:bg-slate-950" dir="rtl"
     x-data="{ device: @js($device) }"
     @pb-drop.window="$wire.call('dropBlock', $event.detail.type, $event.detail.index, $event.detail.parent)">
    {{-- Preview area (left) --}}
    <div class="flex min-w-0 flex-1 flex-col">
        <div class="flex items-center justify-between gap-3 border-b border-slate-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
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

        <div class="flex min-h-0 flex-1 flex-col overflow-y-auto p-4" x-data="{ device: @js($device) }"
            <div class="mx-auto w-full overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition-all duration-300"
                 :class="{
                    'max-w-[390px]': device==='mobile',
                    'max-w-[768px]': device==='tablet',
                    'max-w-[1024px]': device==='desktop',
                    'max-w-none': device==='wide'
                 }"
                 style="background: {{ $pageSettings['background'] ?? '#f8fafc' }}; color: {{ $pageSettings['text'] ?? '#0f172a' }};">

                @include(config('page-builder.preview_partials.header', 'general::layouts.partials.header'))

                <div class="min-h-96 p-4">
                    <div class="h-2 rounded border border-dashed border-brand-300 bg-brand-50/40"
                         data-dropzone @dragover.prevent @drop.prevent="$wire.call('dropBlock', $event.dataTransfer.getData('text/plain'), 0)"></div>

                    @forelse ($document as $index => $block)
                        <div class="group relative my-2 cursor-pointer rounded-xl border-2 p-1 transition-colors {{ $selected === $index ? 'border-brand-500 bg-brand-50/40' : 'border-transparent hover:border-brand-200' }}"
                             wire:click="selectBlock({{ $index }})" wire:key="pb-block-{{ $index }}">
                            <div class="absolute start-2 top-2 z-10 flex gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                                <button type="button" wire:click.stop="moveBlock({{ $index }}, -1)" class="rounded bg-white px-2 py-1 text-[10px] shadow">↑</button>
                                <button type="button" wire:click.stop="moveBlock({{ $index }}, 1)" class="rounded bg-white px-2 py-1 text-[10px] shadow">↓</button>
                                <button type="button" wire:click.stop="removeBlock({{ $index }})" class="rounded bg-rose-600 px-2 py-1 text-[10px] text-white shadow">حذف</button>
                            </div>
                            @livewire('page-builder.component.'.$block['type'], [
                                'settings' => $block['settings'],
                                'children' => $block['children'] ?? [],
                                'editable' => true,
                                'path' => (string) $index,
                            ], key('pb-'.$index.'-'.$block['type']))
                        </div>
                        <div class="h-2 rounded border border-dashed border-brand-300 bg-brand-50/40"
                             data-dropzone @dragover.prevent @drop.prevent="$wire.call('dropBlock', $event.dataTransfer.getData('text/plain'), {{ $index + 1 }})"></div>
                    @empty
                        <div class="my-6 rounded-xl border border-dashed border-slate-300 p-14 text-center text-sm text-slate-400">
                           _componentها را از سمت راست بکشید و اینجا رها کنید.
                        </div>
                    @endforelse
                </div>

                @include(config('page-builder.preview_partials.footer', 'general::layouts.partials.footer'))
            </div>
        </div>

    {{-- Tools sidebar (right) --}}
    <aside class="flex h-full w-80 shrink-0 flex-col overflow-y-auto border-l border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-200 p-4 dark:border-slate-800">
            <h2 class="text-sm font-extrabold text-slate-900 dark:text-white">ابزارهای صفحه</h2>
            <p class="mt-1 text-[11px] text-slate-500">_componentها را بکشید و در محل دلخواه پیشنمایش رها کنید.</p>
        </div>

        <div class="grid grid-cols-2 gap-2 p-3">
            @foreach ($components as $type => $component)
                <button type="button" draggable="true"
                        x-on:dragstart="$event.dataTransfer.setData('text/plain', '{{ $type }}'); $event.dataTransfer.effectAllowed='copy'"
                        class="cursor-grab flex flex-col items-center gap-2 rounded-xl border border-slate-200 p-3 text-center transition-colors hover:border-brand-400 hover:bg-brand-50 active:cursor-grabbing dark:border-slate-700 dark:hover:bg-brand-500/10">
                    <i class="{{ $component['icon'] }} text-lg text-brand-500"></i>
                    <span class="text-[11px] font-bold text-slate-700 dark:text-slate-200">{{ $component['label'] }}</span>
                </button>
            @endforeach
        </div>

        @if ($selected !== null && isset($document[$selected]))
            <div class="border-t border-slate-200 p-4 dark:border-slate-800">
                <h3 class="mb-3 text-xs font-extrabold text-slate-800 dark:text-white">تنظیمات_component</h3>
                @php($definition = $components[$document[$selected]['type']] ?? ['fields' => []])
                @foreach ($definition['fields'] ?? [] as $key => $field)
                    <div class="mb-3">
                        <label class="mb-1 block text-[11px] font-bold text-slate-600 dark:text-slate-300">{{ $field['label'] }}</label>
                        @switch($field['type'])
                            @case('switch')
                                <input type="checkbox" wire:model.live="document.{{ $selected }}.settings.{{ $key }}" class="rounded border-slate-300 text-brand-500">
                                @break
                            @case('select')
                                <select wire:model.live="document.{{ $selected }}.settings.{{ $key }}" class="w-full rounded-lg border border-slate-200 px-2 py-1.5 text-xs dark:border-slate-700 dark:bg-slate-900">
                                    @foreach ($field['options'] ?? [] as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @break
                            @case('model')
                                <select wire:model.live="document.{{ $selected }}.settings.{{ $key }}" class="w-full rounded-lg border border-slate-200 px-2 py-1.5 text-xs dark:border-slate-700 dark:bg-slate-900">
                                    <option value="">همه</option>
                                    @foreach ($categories as $id => $label)
                                        <option value="{{ $id }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @break
                            @case('textarea')
                                <textarea rows="3" wire:model.live.debounce.300ms="document.{{ $selected }}.settings.{{ $key }}" class="w-full rounded-lg border border-slate-200 px-2 py-1.5 text-xs dark:border-slate-700 dark:bg-slate-900"></textarea>
                                @break
                            @case('color')
                                <input type="color" wire:model.live="document.{{ $selected }}.settings.{{ $key }}" class="h-9 w-full rounded border border-slate-200">
                                @break
                            @case('number')
                                <input type="number" wire:model.live.debounce.300ms="document.{{ $selected }}.settings.{{ $key }}" class="w-full rounded-lg border border-slate-200 px-2 py-1.5 text-xs dark:border-slate-700 dark:bg-slate-900">
                                @break
                            @default
                                <input type="text" wire:model.live.debounce.300ms="document.{{ $selected }}.settings.{{ $key }}" class="w-full rounded-lg border border-slate-200 px-2 py-1.5 text-xs dark:border-slate-700 dark:bg-slate-900">
                        @endswitch
                    </div>
                @endforeach

                <div class="mt-4 border-t border-slate-200 pt-3 dark:border-slate-800">
                    <p class="mb-2 text-[11px] font-bold text-slate-600">نمایش در دستگاهها</p>
                    @foreach (['wide' => 'عریض', 'desktop' => 'دسکتاپ', 'tablet' => 'تبلت', 'mobile' => 'موبایل'] as $key => $label)
                        <button type="button" wire:click="toggleDevice({{ $selected }}, '{{ $key }}')"
                                class="mb-1 ms-1 rounded-md px-2 py-1 text-[10px] font-bold {{ ($document[$selected]['devices'][$key] ?? true) ? 'bg-brand-500 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $label }}</button>
                    @endforeach
                </div>
            </div>
        @endif
    </aside>
</div>

<script>
    window.pageBuilderDrop = function (type, index, parent) {
        const host = [...document.querySelectorAll('[wire\\:snapshot]')]
            .find((el) => (el.getAttribute('wire:snapshot') || '').includes('"name":"page-builder.builder"') || (el.getAttribute('wire:snapshot') || '').includes('"name":"elearnac.page-builder.livewire.builder"'));
        if (! host) return;
        window.Livewire.find(host.getAttribute('wire:id')).call('dropBlock', type, index, parent || '');
    };
</script>
