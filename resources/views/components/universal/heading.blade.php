@php
    $level = in_array($setting('level','h2'), ['h1','h2','h3','h4','h5','h6'], true) ? $setting('level','h2') : 'h2';
    $title = (string) $setting('title', '');
    $fallback = $variant === 'page-title' ? data_get(request()->route()?->parameter('slug'), 'title', '') : '';
    $title = $title !== '' ? $title : (string) ($definition['label'] ?? $fallback);
    $subtitle = (string) $setting('subtitle', '');
    $logoUrl = $image ?: ($items->first()['image'] ?? null);
@endphp
<div class="{{ $classes }}" style="{{ $style }}">
    @if ($variant === 'logo')
        @if ($logoUrl)
            <a href="{{ e($setting('link','/')) }}" class="inline-block"><img src="{{ e($logoUrl) }}" alt="{{ e($setting('title','لوگوی سایت')) }}" style="width:{{ (int) $setting('width',140) }}px;height:auto"></a>
        @else
            <a href="{{ e($setting('link','/')) }}" class="pb-title text-xl">{{ e($setting('title','لوگوی سایت')) }}</a>
        @endif
    @else
        <{{ $level }} class="pb-title m-0" style="margin-block-end:{{ (int) $setting('margin',12) }}px">{{ e($title) }}</{{ $level }}>
        @if ($subtitle !== '')
            <p class="pb-muted mt-1 text-sm">{{ e($subtitle) }}</p>
        @endif
    @endif
</div>
