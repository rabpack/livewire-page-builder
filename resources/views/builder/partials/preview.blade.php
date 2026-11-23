@php
    $deviceClass = $device === 'mobile' ? 'max-w-[390px]' : ($device === 'tablet' ? 'max-w-[768px]' : ($device === 'desktop' ? 'max-w-[1024px]' : 'max-w-none'));
    $deviceWidth = match ($device) {
        'mobile' => '390px',
        'tablet' => '768px',
        'desktop' => '1024px',
        default => 'none',
    };
@endphp
<div class="pb-page mx-auto w-full overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition-all duration-300 dark:border-slate-800 {{ $deviceClass }}"
     data-page-builder-preview
     x-data="{}"
     style="max-width: {{ $deviceWidth }}; {{ app(\Elearnac\PageBuilder\Services\StyleCompiler::class)->pageVariables($pageSettings) }};">

    @if ($pageSettings['header'] ?? true)
        <div wire:ignore>
            @include(config('page-builder.preview_partials.header', 'general::layouts.partials.header'))
        </div>
    @endif

    <div class="min-h-96 p-4">
        <div class="pb-dropzone my-2 flex min-h-[72px] flex-col items-center justify-center gap-1 rounded-xl border-2 border-dashed border-brand-300 bg-brand-50/40 text-center"
             data-dropzone data-path=""
             ondragover="event.preventDefault()"
             ondragenter="event.currentTarget.classList.add('pb-drop-active')"
             ondragleave="event.currentTarget.classList.remove('pb-drop-active')"
             ondrop="event.preventDefault(); event.currentTarget.classList.remove('pb-drop-active'); window.dispatchEvent(new CustomEvent('pb-drop',{detail:{type:event.dataTransfer.getData('text/plain'),index:0,parent:''}}))"
             @click="panelTab='add'">
            <i class="far fa-square-plus text-xl text-brand-400"></i>
            <span class="text-[11px] font-bold text-brand-600">المانها را از پنل سمت راست بکشید و اینجا رها کنید</span>
            <span class="text-[10px] text-slate-400">برای شروع، همانند Elementor یک المان را داخل ناحیه بکشید</span>
        </div>

        @forelse ($document as $index => $block)
            @php
                $visible = data_get($block, 'devices.'.$device, true);
            @endphp
            <div class="group relative my-2 rounded-xl border-2 p-1 transition-colors {{ ! $visible ? 'hidden' : '' }} {{ $selected === $index ? 'border-brand-500 bg-brand-50/40' : 'border-transparent hover:border-brand-200' }}"
                 draggable="true"
                 ondragstart="event.dataTransfer.setData('text/plain','move:{{ $index }}')"
                 wire:click="selectBlock({{ $index }})" wire:key="pb-block-{{ $index }}">
                <div class="absolute start-2 top-2 z-10 flex gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                    <button type="button" wire:click.stop="moveBlock({{ $index }}, -1)" class="grid size-6 place-items-center rounded bg-white text-[10px] shadow hover:text-brand-600" title="جابهجایی به بالا">↑</button>
                    <button type="button" wire:click.stop="moveBlock({{ $index }}, 1)" class="grid size-6 place-items-center rounded bg-white text-[10px] shadow hover:text-brand-600" title="جابهجایی به پایین">↓</button>
                    <button type="button" wire:click.stop="duplicateBlock({{ $index }})" class="rounded bg-white px-2 py-1 text-[10px] shadow hover:text-brand-600" title="کپی المان">کپی</button>
                    <button type="button" wire:click.stop="removeBlock({{ $index }})" class="rounded bg-rose-600 px-2 py-1 text-[10px] text-white shadow" title="حذف المان">حذف</button>
                </div>
                @livewire('page-builder.component.'.$block['type'], app(\Elearnac\PageBuilder\ComponentRegistry::class)->mountProps($block['type'], [
                    'settings' => $block['settings'],
                    'children' => $block['children'] ?? [],
                    'editable' => true,
                    'path' => (string) $index,
                ]), key('pb-'.$index.'-'.$block['type'].'-'.$previewVersion))
            </div>

            <div class="pb-dropzone my-1 flex min-h-[28px] items-center justify-center rounded-lg border border-dashed border-brand-300 bg-brand-50/30 text-[10px] text-brand-500"
                 data-dropzone data-path=""
                 ondragover="event.preventDefault()"
                 ondragenter="event.currentTarget.classList.add('pb-drop-active')"
                 ondragleave="event.currentTarget.classList.remove('pb-drop-active')"
                 ondrop="event.preventDefault(); event.currentTarget.classList.remove('pb-drop-active'); window.dispatchEvent(new CustomEvent('pb-drop',{detail:{type:event.dataTransfer.getData('text/plain'),index:{{ $index + 1 }},parent:''}}))">
                <span class="pointer-events-none flex items-center gap-1"><i class="far fa-plus text-[9px]"></i>رها کنید تا المان در اینجا قرار گیرد</span>
            </div>
        @empty
            <div class="my-4 flex flex-col items-center gap-2 rounded-xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center dark:border-slate-700 dark:bg-slate-900/40" @click="panelTab='add'">
                <span class="grid size-12 place-items-center rounded-full bg-brand-50 text-brand-500 dark:bg-brand-500/10"><i class="far fa-cubes text-xl"></i></span>
                <p class="text-xs font-extrabold text-slate-600 dark:text-slate-300">صفحه شما هنوز خالی است</p>
                <p class="text-[11px] text-slate-400">المانها را از پنل سمت راست بکشید و در ناحیه بالا رها کنید تا صفحه ساخته شود.</p>
            </div>
        @endforelse
    </div>

    @if ($pageSettings['footer'] ?? true)
        <div wire:ignore>
            @include(config('page-builder.preview_partials.footer', 'general::layouts.partials.footer'))
        </div>
    @endif
</div>
