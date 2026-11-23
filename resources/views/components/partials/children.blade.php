@php
    $childrenPath = $path === '' ? '' : $path.'.children';
    $gridZone = $gridZone ?? false;
@endphp
@if ($editable)
    @if (count($children) === 0)
        @include('page-builder::builder.partials.drop-zone', [
            'parent' => $path,
            'index' => 0,
            'compact' => false,
            'gridZone' => $gridZone,
            'hint' => 'المان را داخل این المان بکشید',
        ])
    @else
        @include('page-builder::builder.partials.drop-zone', [
            'parent' => $path,
            'index' => 0,
            'compact' => true,
            'gridZone' => $gridZone,
        ])
    @endif
@endif

@foreach ($children as $index => $child)
    @if (is_array($child) && isset($child['type']))
        @if ($editable)
            @include('page-builder::builder.partials.block-frame', [
                'block' => $child,
                'path' => $childrenPath.'.'.$index,
                'parent' => $path,
                'index' => $index,
            ])
        @else
            @livewire('page-builder.component.'.$child['type'], app(\Elearnac\PageBuilder\ComponentRegistry::class)->mountProps($child['type'], [
                'settings' => $child['settings'],
                'children' => $child['children'] ?? [],
                'editable' => false,
                'path' => $childrenPath.'.'.$index,
            ]), key($childrenPath.'-'.$index.'-'.$child['type']))
        @endif
    @endif
    @if ($editable)
        @include('page-builder::builder.partials.drop-zone', [
            'parent' => $path,
            'index' => $index + 1,
            'compact' => true,
            'gridZone' => $gridZone,
        ])
    @endif
@endforeach
