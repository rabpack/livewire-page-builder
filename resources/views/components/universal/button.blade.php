@php
    $link = (string) $setting('link', '#');
    $label = (string) $setting('label', 'ادامه مطلب');
    $styleKind = (string) $setting('style', 'solid');
    $classes = $classes.($styleKind === 'outline' ? ' pb-button-outline' : '').($styleKind === 'ghost' ? ' pb-button-ghost' : '');
@endphp
<div class="{{ $classes }}" style="{{ $style }}">
    @switch($variant)
        @case('anchor')
            <a href="#{{ e($setting('anchor','section-1')) }}" data-pb-anchor="{{ e($setting('anchor','section-1')) }}" class="pb-link text-sm">{{ e($label) }}</a>
            @break
        @case('read-more')
            <a href="{{ e($link) }}" class="pb-link inline-flex items-center gap-2 text-sm">{{ e($label) }} <i class="far fa-arrow-left"></i></a>
            @break
        @default
            <a href="{{ e($link) }}" target="{{ e($setting('target','_self')) }}" class="pb-button {{ $setting('block') ? 'w-full' : '' }} {{ $setting('button_size','md') === 'lg' ? 'px-6 py-3' : ($setting('button_size','md') === 'sm' ? 'px-3 py-1.5 text-xs' : '') }}">
                @if ($setting('icon'))<i class="{{ e($setting('icon')) }}"></i>@endif
                <span>{{ e($label) }}</span>
            </a>
    @endswitch
</div>
