@php
    $definition = $components[$document[$selected]['type']] ?? ['fields' => []];
    $fieldGroups = ['content' => [], 'style' => [], 'advanced' => []];
    foreach ($definition['fields'] ?? [] as $fieldKey => $field) {
        $section = $field['section'] ?? (in_array($field['type'] ?? '', ['color', 'number']) ? 'style' : 'content');
        $fieldGroups[$section][$fieldKey] = $field;
    }
@endphp

<div class="mb-3 flex items-center gap-2 rounded-lg border border-brand-200 bg-brand-50 p-2 dark:border-brand-500/20 dark:bg-brand-500/10">
    <span class="grid size-7 place-items-center rounded-md bg-brand-500 text-white"><i class="{{ $definition['icon'] ?? 'far fa-cube' }} text-xs"></i></span>
    <div class="min-w-0">
        <p class="truncate text-xs font-extrabold text-slate-800 dark:text-slate-100">{{ $definition['label'] ?? $document[$selected]['type'] }}</p>
        <p class="text-[10px] text-slate-500">تنظیمات المان انتخابشده</p>
    </div>
</div>

<div class="mb-3 flex gap-1 rounded-lg bg-slate-100 p-1 dark:bg-slate-800">
    @foreach (['content' => ['محتوا', 'far fa-file-alt'], 'style' => ['استایل', 'far fa-paint-brush'], 'advanced' => ['پیشرفته', 'far fa-cogs']] as $key => $tab)
        <button type="button" @click="settingsTab='{{ $key }}'"
                :class="settingsTab==='{{ $key }}' ? 'bg-white text-brand-600 shadow dark:bg-slate-900' : 'text-slate-500 hover:text-brand-600'"
                class="flex flex-1 items-center justify-center gap-1 rounded-md px-2 py-1.5 text-[11px] font-bold">
            <i class="{{ $tab[1] }} text-[10px]"></i>{{ $tab[0] }}
        </button>
    @endforeach
</div>

@foreach ($fieldGroups as $section => $fields)
    <div x-show="settingsTab==='{{ $section }}'" class="mb-4 space-y-2">
        @forelse ($fields as $key => $field)
            @include('page-builder::builder.partials.field', [
                'key' => $key,
                'field' => $field,
                'selected' => $selected,
                'document' => $document,
                'fonts' => $fonts,
                'categories' => $categories,
                'fieldPresentation' => $fieldPresentation,
            ])
        @empty
            <p class="rounded-lg border border-dashed border-slate-200 p-3 text-center text-[11px] text-slate-400">تنظیمهایی برای این بخش وجود ندارد.</p>
        @endforelse
    </div>
@endforeach

<div x-show="settingsTab==='advanced'" class="rounded-xl border border-slate-200 bg-white p-2.5 dark:border-slate-700 dark:bg-slate-800/60">
    <p class="mb-2 flex items-center gap-1.5 text-[11px] font-extrabold text-slate-700 dark:text-slate-200">
        <i class="far fa-desktop text-[10px] text-brand-500"></i>نمایش بر اساس دستگاه
    </p>
    @foreach (['desktop' => ['دسکتاپ', 'far fa-desktop'], 'tablet' => ['تبلت', 'far fa-tablet'], 'mobile' => ['موبایل', 'far fa-mobile-alt'], 'wide' => ['عریض', 'far fa-expand']] as $key => $deviceOption)
        @php($enabled = data_get($document, $selected.'.devices.'.$key, true))
        <button type="button" wire:click="toggleDevice({{ $selected }}, '{{ $key }}')"
                class="mb-1 flex w-full items-center justify-between rounded-md px-2 py-1.5 text-[11px] {{ $enabled ? 'bg-brand-500/10 text-brand-600 dark:text-brand-300' : 'text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
            <span class="flex items-center gap-1.5"><i class="{{ $deviceOption[1] }} text-[10px]"></i>{{ $deviceOption[0] }}</span>
            <span class="text-[10px] font-bold">{{ $enabled ? 'فعال' : 'غیرفعال' }}</span>
        </button>
    @endforeach
</div>
