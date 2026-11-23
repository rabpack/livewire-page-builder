<div class="flex h-screen w-full overflow-hidden bg-slate-100 text-slate-800 dark:bg-slate-950 dark:text-slate-100"
     dir="rtl"
     x-data="{ panelTab: 'add', settingsTab: 'content', toolSearch: '' }"
     @pb-drop.window="$wire.call('dropBlock', $event.detail.type, $event.detail.index, $event.detail.parent); panelTab='settings'">

    {{-- Right sidebar --}}
    <aside class="flex h-full w-[336px] min-w-[336px] max-w-[336px] shrink-0 grow-0 flex-col border-e border-slate-200 bg-white shadow-xl dark:border-slate-800 dark:bg-slate-900">

        <div class="flex items-center gap-1 border-b border-slate-200 p-2 dark:border-slate-800">
            <button type="button" @click="panelTab='add'" :class="panelTab==='add' ? 'bg-brand-500 text-white' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800'"
                    class="flex-1 rounded-lg px-3 py-2 text-xs font-extrabold transition-colors">
                <i class="far fa-plus me-1"></i>المانها
            </button>
            <button type="button" @click="panelTab='settings'" :class="panelTab==='settings' ? 'bg-brand-500 text-white' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800'"
                    class="flex-1 rounded-lg px-3 py-2 text-xs font-extrabold transition-colors">
                <i class="far fa-sliders-h me-1"></i>تنظیمات
            </button>
            <button type="button" @click="panelTab='page'" :class="panelTab==='page' ? 'bg-brand-500 text-white' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800'"
                    class="flex-1 rounded-lg px-3 py-2 text-xs font-extrabold transition-colors">
                <i class="far fa-file me-1"></i>صفحه
            </button>
            <button type="button" @click="toggleTheme()" class="grid size-9 place-items-center rounded-lg border border-slate-200 text-slate-500 hover:border-brand-400 dark:border-slate-700" aria-label="تغییر پوسته">
                <i class="far fa-moon text-xs" x-show="!darkMode"></i>
                <i class="far fa-sun text-xs" x-show="darkMode" x-cloak></i>
            </button>
        </div>

        <div class="min-h-0 flex-1 overflow-y-auto" x-show="panelTab==='add'">
            @include('page-builder::builder.partials.elements')
        </div>

        <div class="min-h-0 flex-1 overflow-y-auto p-3" x-show="panelTab==='settings'">
            @if ($selected !== null && isset($document[$selected]))
                @include('page-builder::builder.partials.element-settings')
            @else
                <div class="flex flex-col items-center gap-2 rounded-xl border border-dashed border-slate-200 p-8 text-center dark:border-slate-700">
                    <span class="grid size-10 place-items-center rounded-full bg-brand-50 text-brand-500 dark:bg-brand-500/10"><i class="far fa-mouse-pointer"></i></span>
                    <p class="text-xs font-bold text-slate-600 dark:text-slate-300">یک المان را انتخاب کنید</p>
                    <p class="text-[11px] text-slate-400">با کلیک بر روی المان در پیشنمایش، تنظیمات آن درج اینجا نمایش داده میشود.</p>
                </div>
            @endif
        </div>

        <div class="min-h-0 flex-1 overflow-y-auto p-3" x-show="panelTab==='page'">
            @include('page-builder::builder.partials.page-settings')
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
                @foreach (['wide' => ['عریض', 'far fa-expand'], 'desktop' => ['دسکتاپ', 'far fa-desktop'], 'tablet' => ['تبلت', 'far fa-tablet'], 'mobile' => ['موبایل', 'far fa-mobile-alt']] as $key => $deviceOption)
                    <button type="button" wire:click="setDevice('{{ $key }}')"
                            title="پیشنمایش {{ $deviceOption[0] }}"
                            class="flex items-center gap-1 rounded-md px-3 py-1.5 text-xs font-bold transition-colors {{ $device === $key ? 'bg-brand-500 text-white' : 'text-slate-500 hover:bg-white dark:hover:bg-slate-800' }}">
                        <i class="{{ $deviceOption[1] }} text-[10px]"></i>{{ $deviceOption[0] }}
                    </button>
                @endforeach
            </div>

            <div class="flex items-center gap-1">
                <button type="button" wire:click="undo" class="grid size-9 place-items-center rounded-lg border border-slate-200 text-xs font-bold text-slate-600 dark:border-slate-700 dark:text-slate-300" title="واگرد"><i class="far fa-undo"></i></button>
                <button type="button" wire:click="redo" class="grid size-9 place-items-center rounded-lg border border-slate-200 text-xs font-bold text-slate-600 dark:border-slate-700 dark:text-slate-300" title="بازگرد"><i class="far fa-redo"></i></button>
                <button type="button" wire:click="save" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-4 py-2 text-xs font-bold text-white hover:bg-brand-600">
                    <i class="far fa-save"></i>ذخیره صفحه
                </button>
            </div>
        </div>

        <div class="min-h-0 flex-1 overflow-y-auto p-4">
            @include('page-builder::builder.partials.preview')
        </div>
    </div>
</div>
