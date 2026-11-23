@php
    $images = (array) data_get($document, $selected.'.settings.'.$key, []);
@endphp
<div class="space-y-2" @click.capture="$wire.setFileKey('{{ $key }}')">
    @forelse ($images as $imageIndex => $imageId)
        <div class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white p-1.5 dark:border-slate-700 dark:bg-slate-800">
            <img src="{{ app(\Elearnac\PageBuilder\Services\MediaResolver::class)->url($imageId) }}"
                 class="size-11 rounded-md object-cover" alt="{{ $field['label'] }}">
            <span class="flex-1 truncate text-[10px] text-slate-500">#{{ $imageId }}</span>
            <button type="button" wire:click="removeImage({{ $imageIndex }}, '{{ $key }}')"
                    class="grid size-6 place-items-center rounded-md bg-rose-50 text-xs text-rose-600 hover:bg-rose-100 dark:bg-rose-500/10"
                    aria-label="حذف تصویر">×</button>
        </div>
    @empty
        <p class="rounded-lg border border-dashed border-slate-300 p-2 text-center text-[10px] text-slate-400">هنوز تصویری انتخاب نشده است.</p>
    @endforelse

    @if (config('page-builder.file_manager.component'))
        <div class="overflow-hidden rounded-lg border border-slate-200 dark:border-slate-700">
            <livewire:dynamic-component :is="config('page-builder.file_manager.component')"
                :name="config('page-builder.file_manager.name')"
                title="انتخاب تصویر از مدارک سایت"
                :multipleSelect="true"
                :fileTypes="['image/png','image/jpeg','image/webp','image/svg']" />
        </div>
    @endif
</div>
