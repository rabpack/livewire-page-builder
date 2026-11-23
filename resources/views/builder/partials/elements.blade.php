<div class="p-3">
    @if ($activeParent !== '')
        <div class="mb-3 flex items-center justify-between gap-2 rounded-lg border border-brand-300 bg-brand-50 p-2 dark:border-brand-500/30 dark:bg-brand-500/10">
            <div class="min-w-0">
                <p class="text-[10px] text-brand-600 dark:text-brand-300">المانها داخل این المان اضافه میشوند</p>
                <p class="truncate text-xs font-extrabold text-slate-700 dark:text-slate-200">{{ $activeParentLabel }}</p>
            </div>
            <button type="button" wire:click="clearAddTarget" title="بازگشت به صفحه"
                    class="grid size-7 shrink-0 place-items-center rounded-md bg-white text-brand-600 shadow-sm hover:bg-brand-100 dark:bg-slate-800 dark:text-brand-300">
                <i class="far fa-times text-[10px]"></i>
            </button>
        </div>
    @endif

    <div class="relative">
        <i class="far fa-search absolute inset-y-0 start-3 my-auto text-xs text-slate-400"></i>
        <input type="search" x-model="toolSearch" placeholder="جستجوی المان..."
               class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 ps-9 pe-3 text-xs outline-none focus:border-brand-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
    </div>
    <p class="mt-1.5 text-[10px] text-slate-400">برای افزودن، روی المان کلیک کنید یا آن را داخل ناحیه مورد نظر بکشید.</p>
</div>

@foreach ($groups as $group => $items)
    <div class="px-3 pb-4" x-data="{ open: true }">
        <button type="button" @click="open=!open"
                class="mb-2 flex w-full items-center justify-between rounded-md px-1 py-1 text-xs font-extrabold text-slate-700 transition-colors hover:text-brand-600 dark:text-slate-200">
            <span class="flex items-center gap-2"><i class="far fa-layer-group text-[10px] text-brand-500"></i>{{ $group }}</span>
            <i class="far fa-chevron-up text-[10px]" :class="open ? '' : 'rotate-180'"></i>
        </button>
        <div class="grid grid-cols-2 gap-2" x-show="open">
            @foreach ($items as $type => $component)
                <button type="button" draggable="true"
                        title="اضافه کردن {{ $component['label'] }}"
                        wire:click="addBlock('{{ $type }}')"
                        @click="panelTab='settings'"
                        x-on:dragstart="$event.dataTransfer.setData('text/plain', '{{ $type }}'); $event.dataTransfer.effectAllowed='copy'"
                        x-show="'{{ $component['label'] }}'.includes(toolSearch) || '{{ $type }}'.includes(toolSearch)"
                        class="flex cursor-grab flex-col items-center gap-1.5 rounded-xl border border-slate-200 bg-white p-2.5 text-center transition-colors hover:border-brand-400 hover:bg-brand-50 active:cursor-grabbing dark:border-slate-700 dark:bg-slate-800 dark:hover:bg-brand-500/10">
                    <span class="grid size-9 place-items-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-300">
                        <i class="{{ $component['icon'] }} text-sm"></i>
                    </span>
                    <span class="line-clamp-2 text-[10px] font-bold leading-4 text-slate-700 dark:text-slate-200">{{ $component['label'] }}</span>
                </button>
            @endforeach
        </div>
    </div>
@endforeach
