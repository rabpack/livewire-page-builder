@php
    $settingsPath = $settingsPath ?? 'document.'.$selected.'.settings.'.$key;
    $repeater = (array) data_get($document, $settingsPath, []);
@endphp
<div class="space-y-2">
    @forelse ($repeater as $fieldIndex => $formField)
        <div class="rounded-lg border border-slate-200 bg-white p-2 dark:border-slate-700 dark:bg-slate-800">
            <div class="mb-2 flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-500">مورد {{ $fieldIndex + 1 }}</span>
                <button type="button" wire:click="removeRepeaterItem('{{ $key }}', {{ $fieldIndex }})"
                        class="rounded px-1.5 py-0.5 text-[10px] text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10">حذف</button>
            </div>
            @foreach ((array) data_get($field, 'subfields', []) as $subfield => $subfieldType)
                @if ($subfieldType === 'switch')
                    <label class="mb-1.5 flex items-center gap-2 text-[10px] text-slate-600 dark:text-slate-300">
                        <input type="checkbox" class="size-3.5 rounded border-slate-300 text-brand-500"
                               wire:model.live="{{ $settingsPath }}.{{ $fieldIndex }}.{{ $subfield }}">
                        {{ $subfield }}
                    </label>
                @elseif ($subfieldType === 'select')
                    <select wire:model.live="{{ $settingsPath }}.{{ $fieldIndex }}.{{ $subfield }}"
                            class="mb-1.5 w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[11px] dark:border-slate-700 dark:bg-slate-900">
                        @foreach (($subfield === 'type' ? ['text'=>'متن','email'=>'ایمیل','textarea'=>'پیام','number'=>'عدد','tel'=>'تلفن'] : []) as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                @elseif ($subfieldType === 'textarea')
                    <textarea rows="2" wire:model.live.debounce.300ms="{{ $settingsPath }}.{{ $fieldIndex }}.{{ $subfield }}"
                              placeholder="{{ $subfield }}" class="mb-1.5 w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[11px] dark:border-slate-700 dark:bg-slate-900"></textarea>
                @elseif ($subfieldType === 'editor' && config('page-builder.editor.component'))
                    <x-dynamic-component :component="config('page-builder.editor.component')"
                        id="pb-editor-{{ $selected }}-{{ $key }}-{{ $fieldIndex }}-{{ $subfield }}"
                        name="{{ $settingsPath }}.{{ $fieldIndex }}.{{ $subfield }}"
                        :defaultValue="data_get($document, $settingsPath.'.'.$fieldIndex.'.'.$subfield, '')"
                        :isFileUpload="false" :menuBar="false" :toolbar="config('page-builder.editor.toolbar')" />
                @else
                    <input type="text" wire:model.live.debounce.300ms="{{ $settingsPath }}.{{ $fieldIndex }}.{{ $subfield }}"
                           placeholder="{{ $subfield }}" class="mb-1.5 w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[11px] dark:border-slate-700 dark:bg-slate-900">
                @endif
            @endforeach
        </div>
    @empty
        <p class="text-center text-[10px] text-slate-400">موردی برای نمایش وجود ندارد.</p>
    @endforelse

    <button type="button" wire:click="addRepeaterItem('{{ $key }}')"
            class="flex w-full items-center justify-center gap-1.5 rounded-lg border border-dashed border-brand-400 py-2 text-[11px] font-bold text-brand-600 hover:bg-brand-50 dark:text-brand-300 dark:hover:bg-brand-500/10">
        <i class="far fa-plus text-[10px]"></i> {{ $field['label'] }} را اضافه کنید
    </button>
</div>
