@php
    $columns = max(1, min(6, (int) $setting('columns', 3)));
    $carousel = str_contains((string) ($definition['label'] ?? ''), 'اسلاید')
        || in_array($type, ['course-carousel', 'product-carousel', 'portfolio', 'loop-grid'], true);
    $swiperConfig = json_encode([
        'slidesPerView' => max(1, min(6, (int) $setting('slides_desktop', $columns))),
        'spaceBetween' => (int) $setting('gap', 16),
        'loop' => (bool) $setting('loop', false),
        'autoplay' => $setting('autoplay', false) ? ['delay' => 3500, 'pauseOnMouseEnter' => true] : false,
        'keyboard' => ['enabled' => true],
    ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
@endphp
<div class="{{ $classes }}" style="{{ $style }};--pb-columns:{{ $columns }}">
    @if ($items->isEmpty())
        <p class="pb-card pb-muted text-xs">{{ e($setting('empty','موردی برای نمایش وجود ندارد.')) }}</p>
    @elseif ($carousel)
        <div class="swiper" x-init="new Swiper($el, {{ $swiperConfig }})">
            <div class="swiper-wrapper">
                @foreach ($items as $item)
                    <div class="swiper-slide h-auto">
                        @include('page-builder::components.universal.partials.item-card', ['item' => $item, 'settings' => $settings, 'variant' => $variant, 'setting' => $setting])
                    </div>
                @endforeach
            </div>
            @if ($setting('pagination',true))<div class="swiper-pagination"></div>@endif
            @if ($setting('arrows',true))<div class="swiper-button-prev"></div><div class="swiper-button-next"></div>@endif
        </div>
    @elseif ($variant === 'archive' && $setting('group_by','none') === 'month')
        <div class="space-y-4">
            @foreach ($items->groupBy(fn ($item) => $item['meta'] ?? '') as $group => $grouped)
                <div><h3 class="mb-2 text-sm font-extrabold">{{ e($group ?: 'سایر') }}</h3><div class="pb-grid">@foreach ($grouped as $item)@include('page-builder::components.universal.partials.item-card', ['item'=>$item,'settings'=>$settings,'variant'=>$variant,'setting'=>$setting])@endforeach</div></div>
            @endforeach
        </div>
    @else
        <div class="pb-grid">
            @foreach ($items as $item)
                @include('page-builder::components.universal.partials.item-card', ['item' => $item, 'settings' => $settings, 'variant' => $variant, 'setting' => $setting])
            @endforeach
        </div>
    @endif
</div>
