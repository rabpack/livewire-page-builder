@php
    $designDevice = $designDevice ?? 'desktop';
    $basePath = 'document.'.$selected.'.settings.'.$key;
    $fieldPath = $designDevice === 'desktop'
        ? $basePath
        : 'document.'.$selected.'.settings.responsive.'.$designDevice.'.'.$key;
    $fieldValue = data_get($document, $fieldPath, data_get($document, $basePath, $field['default'] ?? null));
@endphp
@php($hint = $fieldPresentation->hint($key, (string) ($field['type'] ?? 'text')))
<div class="rounded-xl border border-slate-200 bg-white p-2.5 dark:border-slate-700 dark:bg-slate-800/60">
    <div class="mb-1.5 flex items-center gap-1.5">
        <span class="grid size-5 shrink-0 place-items-center rounded bg-brand-50 text-[10px] text-brand-600 dark:bg-brand-500/10 dark:text-brand-300">
            <i class="{{ $fieldPresentation->icon((string) ($field['type'] ?? 'text')) }}"></i>
        </span>
        <label class="text-[11px] font-bold text-slate-700 dark:text-slate-200">{{ $field['label'] }}</label>
    </div>

    @switch($field['type'] ?? 'text')
        @case('switch')
            <label class="inline-flex cursor-pointer items-center gap-2" role="switch">
                <input type="checkbox" class="peer sr-only" wire:model.live="{{ $fieldPath }}">
                <span class="relative h-5 w-9 rounded-full bg-slate-300 transition-colors peer-checked:bg-brand-500 dark:bg-slate-600 after:absolute after:start-0.5 after:top-0.5 after:size-4 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:after:translate-x-4 rtl:peer-checked:after:-translate-x-4"></span>
                <span class="text-[10px] text-slate-500">{{ $fieldValue ? 'فعال' : 'غیرفعال' }}</span>
            </label>
            @break

        @case('font')
        @case('select')
        @case('model')
            <div class="relative">
                <select wire:model.live="{{ $fieldPath }}" class="w-full appearance-none rounded-lg border border-slate-200 bg-slate-50 py-1.5 pe-8 ps-2 text-xs outline-none focus:border-brand-400 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                    @if (($field['type'] ?? '') === 'model')<option value="">همه دستهبندیها</option>@endif
                    @foreach (($field['type'] ?? '') === 'font' ? $fonts : (($field['type'] ?? '') === 'model' ? $categories : ($field['options'] ?? [])) as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <i class="far fa-chevron-down pointer-events-none absolute inset-y-0 end-2 my-auto text-[10px] text-slate-400"></i>
            </div>
            @break

        @case('number')
            <div class="flex items-center gap-2">
                <input type="range" min="{{ $field['min'] ?? 0 }}" max="{{ $field['max'] ?? 10000 }}" step="{{ $field['step'] ?? 1 }}"
                       wire:model.live="{{ $fieldPath }}" class="h-1.5 flex-1 accent-brand-500">
                <input type="number" min="{{ $field['min'] ?? 0 }}" max="{{ $field['max'] ?? 10000 }}"
                       wire:model.live="{{ $fieldPath }}" class="w-16 rounded-lg border border-slate-200 bg-slate-50 px-1.5 py-1 text-center text-xs dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
            </div>
            @break

        @case('color')
            <div class="flex items-center gap-2">
                <input type="color" wire:model.live="{{ $fieldPath }}" class="size-9 cursor-pointer rounded-md border border-slate-200 bg-transparent p-0.5 dark:border-slate-700">
                <input type="text" dir="ltr" wire:model.live="{{ $fieldPath }}" class="flex-1 rounded-lg border border-slate-200 bg-slate-50 px-2 py-1.5 text-xs dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
            </div>
            @break

        @case('textarea')
            <textarea rows="3" wire:model.live.debounce.300ms="{{ $fieldPath }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-2 py-1.5 text-xs outline-none focus:border-brand-400 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"></textarea>
            @break

        @case('editor')
            @if (config('page-builder.editor.component'))
                <x-dynamic-component :component="config('page-builder.editor.component')"
                    id="pb-editor-{{ str_replace('.', '-', (string) $selected) }}-{{ $key }}"
                    name="{{ $fieldPath }}"
                    :defaultValue="$fieldValue"
                    :isFileUpload="false" :menuBar="false" :toolbar="config('page-builder.editor.toolbar')" />
            @else
                <textarea rows="5" wire:model.live.debounce.300ms="{{ $fieldPath }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-2 py-1.5 text-xs dark:border-slate-700 dark:bg-slate-900"></textarea>
            @endif
            @break

        @case('files')
            @include('page-builder::builder.partials.files-field', ['images' => (array) $fieldValue])
            @break

        @case('repeater')
            @include('page-builder::builder.partials.repeater-field', ['settingsPath' => $fieldPath])
            @break

        @default
            <input type="text" wire:model.live.debounce.300ms="{{ $fieldPath }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-2 py-1.5 text-xs outline-none focus:border-brand-400 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
            @break
    @endswitch

    @if ($hint !== '')
        <p class="mt-1.5 text-[10px] leading-4 text-slate-400">{{ $hint }}</p>
    @endif
</div>
