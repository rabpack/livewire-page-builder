@php
    $width = (int) $setting('container_width', 1200);
    $isGrid = in_array($variant, ['grid', 'row'], true);
    $justifyMap = ['start' => 'flex-start', 'center' => 'center', 'end' => 'flex-end', 'between' => 'space-between'];
    $itemsMap = ['start' => 'flex-start', 'center' => 'center', 'end' => 'flex-end', 'stretch' => 'stretch'];
    $justify = $justifyMap[(string) $setting('justify', 'start')] ?? 'flex-start';
    $alignItems = $itemsMap[(string) $setting('align_items', 'stretch')] ?? 'stretch';
    $gridVariables = $gridVariables ?? '';
@endphp
<div class="{{ $classes }} pb-layout" style="{{ $style }};{{ $gridVariables }};--pb-min-height:{{ (int) $setting('min_height', 0) }}px;--pb-justify:{{ $justify }};--pb-items:{{ $alignItems }};{{ $variant === 'column' ? 'width:100%;' : '' }}">
    @if ($isGrid)
        <div class="pb-grid" style="{{ $gridVariables }};align-items:{{ $setting('align_items','stretch') }}">
            @include('page-builder::components.partials.children', ['children' => $children, 'editable' => $editable, 'path' => $path, 'gridZone' => true])
        </div>
    @elseif ($variant === 'sidebar')
        <aside class="w-full lg:w-72">
            @include('page-builder::components.partials.children', ['children' => $children, 'editable' => $editable, 'path' => $path])
        </aside>
    @else
        <div class="{{ $setting('full_width') ? 'w-full' : 'mx-auto w-full' }}" style="max-width:{{ $setting('full_width') ? '100%' : $width.'px' }}">
            @if ($variant === 'section')
                <section class="py-10">
                    @include('page-builder::components.partials.children', ['children' => $children, 'editable' => $editable, 'path' => $path])
                </section>
            @else
                @include('page-builder::components.partials.children', ['children' => $children, 'editable' => $editable, 'path' => $path])
            @endif
        </div>
    @endif
</div>
