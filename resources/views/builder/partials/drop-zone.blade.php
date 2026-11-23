@php
    $compact ??= false;
    $gridZone ??= false;
    $hint ??= 'المان را اینجا رها کنید';
@endphp
<div class="pb-dropzone {{ $compact ? 'pb-dropzone-compact' : 'pb-dropzone-large' }} {{ $gridZone ? 'pb-dropzone-grid' : '' }}"
     data-dropzone
     data-path="{{ $parent }}"
     data-index="{{ $index }}"
     ondragover="event.preventDefault();event.stopPropagation()"
     ondragenter="event.stopPropagation();event.currentTarget.classList.add('pb-drop-active')"
     ondragleave="event.stopPropagation();event.currentTarget.classList.remove('pb-drop-active')"
     ondrop="event.preventDefault();event.stopPropagation();event.currentTarget.classList.remove('pb-drop-active');window.dispatchEvent(new CustomEvent('pb-drop',{detail:{type:event.dataTransfer.getData('text/plain'),index:{{ $index }},parent:'{{ $parent }}'}}))"
     @click.stop="window.dispatchEvent(new CustomEvent('pb-open-add'))">
    @if ($compact)
        <span class="pb-dropzone-line"><i class="far fa-plus text-[9px]"></i>{{ $hint }}</span>
    @else
        <span class="pb-dropzone-icon"><i class="far fa-square-plus"></i></span>
        <span class="pb-dropzone-title">{{ $hint }}</span>
        <span class="pb-dropzone-help">المانها را از پنل سمت راست بکشید و اینجا رها کنید</span>
    @endif
</div>
