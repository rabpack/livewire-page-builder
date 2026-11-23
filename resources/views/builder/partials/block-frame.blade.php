@php
    $definition = $components[$block['type']] ?? [];
    $label = $definition['label'] ?? $block['type'];
    $icon = $definition['icon'] ?? 'far fa-cube';
    $isSelected = $selected === $path;
    $visible = data_get($block, 'devices.'.$device, true);
@endphp
<div class="pb-block group {{ $isSelected ? 'pb-block-selected' : '' }} {{ $visible ? '' : 'pb-block-hidden' }}"
     data-pb-path="{{ $path }}"
     draggable="true"
     ondragstart="event.stopPropagation();event.dataTransfer.setData('text/plain','move:{{ $path }}');event.dataTransfer.effectAllowed='move'"
     @click.stop="window.dispatchEvent(new CustomEvent('pb-select',{detail:{path:'{{ $path }}'}}))">
    <div class="pb-block-badge">
        <i class="{{ $icon }}"></i><span>{{ $label }}</span>
    </div>
    <div class="pb-block-tools">
        <button type="button" title="جابهجایی به بالا" onclick="event.stopPropagation();window.dispatchEvent(new CustomEvent('pb-move',{detail:{path:'{{ $path }}',direction:-1}}))"><i class="far fa-arrow-up"></i></button>
        <button type="button" title="جابهجایی به پایین" onclick="event.stopPropagation();window.dispatchEvent(new CustomEvent('pb-move',{detail:{path:'{{ $path }}',direction:1}}))"><i class="far fa-arrow-down"></i></button>
        <button type="button" title="کپی المان" onclick="event.stopPropagation();window.dispatchEvent(new CustomEvent('pb-duplicate',{detail:{path:'{{ $path }}'}}))"><i class="far fa-copy"></i></button>
        <button type="button" title="حذف المان" class="pb-block-delete" onclick="event.stopPropagation();window.dispatchEvent(new CustomEvent('pb-remove',{detail:{path:'{{ $path }}'}}))"><i class="far fa-trash-alt"></i></button>
    </div>
    @livewire('page-builder.component.'.$block['type'], app(\Elearnac\PageBuilder\ComponentRegistry::class)->mountProps($block['type'], [
        'settings' => $block['settings'],
        'children' => $block['children'] ?? [],
        'editable' => true,
        'path' => $path,
        'selectedPath' => $selected,
        'device' => $device,
        'previewVersion' => $previewVersion,
    ]), key('pb-'.$path.'-'.$block['type'].'-'.$previewVersion))
</div>
