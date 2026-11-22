@foreach ($children as $index => $child)
    @if ($editable)
        <div class="my-1 h-2 rounded border border-dashed border-brand-300 bg-brand-50/40"
             data-dropzone data-path="{{ $path }}.children"
             ondragover="event.preventDefault()"
             ondrop="event.preventDefault(); window.dispatchEvent(new CustomEvent('pb-drop',{detail:{type:event.dataTransfer.getData('text/plain'),index:{{ $index }},parent:'{{ $path }}.children'}}))"></div>
    @endif
    <div class="{{ $editable ? 'rounded-lg border border-transparent p-1 hover:border-brand-200' : '' }}">
        @livewire('page-builder.component.'.$child['type'], [
            'settings' => $child['settings'],
            'children' => $child['children'] ?? [],
            'editable' => $editable,
            'path' => $path.'.children.'.$index,
        ], key($path.'-child-'.$index.'-'.$child['type']))
    </div>
@endforeach
@if ($editable)
    <div class="my-1 h-2 rounded border border-dashed border-brand-300 bg-brand-50/40"
         data-dropzone data-path="{{ $path }}.children"
         ondragover="event.preventDefault()"
         ondrop="event.preventDefault(); window.dispatchEvent(new CustomEvent('pb-drop',{detail:{type:event.dataTransfer.getData('text/plain'),index:{{ count($children) }},parent:'{{ $path }}.children'}}))"></div>
@endif
