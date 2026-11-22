@php
    $lat = (float) $setting('latitude', 35.6892);
    $lng = (float) $setting('longitude', 51.3890);
    $zoom = (int) $setting('zoom', 14);
    $height = (int) $setting('height', 360);
    $address = (string) $setting('address', '');
@endphp
<div class="{{ $classes }}" style="{{ $style }}">
    @if ($setting('provider','openstreetmap') === 'google' || $variant === 'google-map')
        <iframe title="نقشه" class="w-full rounded-lg" height="{{ $height }}" loading="lazy" src="https://www.google.com/maps?q={{ urlencode($address ?: $lat.','.$lng) }}&z={{ $zoom }}&output=embed"></iframe>
    @else
        <iframe title="نقشه" class="w-full rounded-lg" height="{{ $height }}" loading="lazy" src="https://www.openstreetmap.org/export/embed.html?bbox={{ $lng - 0.02 }}%2C{{ $lat - 0.02 }}%2C{{ $lng + 0.02 }}%2C{{ $lat + 0.02 }}&layer=mapnik&marker={{ $lat }}%2C{{ $lng }}"></iframe>
    @endif
</div>
