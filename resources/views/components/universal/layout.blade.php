@php
    $columns = max(1, min(8, (int) $setting('columns', 3)));
    $columnsTablet = max(1, min(8, (int) $setting('columns_tablet', 2)));
    $columnsMobile = max(1, min(8, (int) $setting('columns_mobile', 1)));
    $width = (int) $setting('container_width', 1200);
    $isGrid = in_array($variant, ['grid', 'row'], true);
    $justifyMap = ['start' => 'flex-start', 'center' => 'center', 'end' => 'flex-end', 'between' => 'space-between'];
    $itemsMap = ['start' => 'flex-start', 'center' => 'center', 'end' => 'flex-end', 'stretch' => 'stretch'];
    $justify = $justifyMap[(string) $setting('justify', 'start')] ?? 'flex-start';
    $alignItems = $itemsMap[(string) $setting('align_items', 'stretch')] ?? 'stretch';
    $columnWidth = (int) $setting('column_width', 100);
@endphp
<div class="{{ $classes }} pb-layout" style="{{ $style }};--pb-columns:{{ $columns }};--pb-columns-mobile:{{ $columnsMobile }};--pb-min-height:{{ (int) $setting('min_height', 0) }}px;--pb-justify:{{ $justify }};--pb-items:{{ $alignItems }};{{ $variant === 'column' ? 'width:'.$columnWidth.'%;' : '' }}">
    @if ($isGrid)
        <div class="pb-grid" style="--pb-columns:{{ $columns }};--pb-columns-mobile:{{ $columnsMobile }};align-items:{{ $setting('align_items','stretch') }}">
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
