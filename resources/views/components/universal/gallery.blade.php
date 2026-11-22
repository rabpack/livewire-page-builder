@php
    $photos = $images;
    if ($photos === [] && $items->isNotEmpty()) {
        $photos = $items->pluck('image')->filter()->values()->all();
    }
    $titles = $items->pluck('title')->filter()->values()->all();
    $swiperConfig = json_encode([
        'slidesPerView' => max(1, min(6, (int) $setting('slides_desktop', 3))),
        'spaceBetween' => (int) $setting('gap', 12),
        'loop' => (bool) $setting('loop', true),
        'autoplay' => $setting('autoplay', true) ? ['delay' => 3500, 'pauseOnMouseEnter' => true] : false,
        'keyboard' => ['enabled' => true],
        'effect' => (string) $setting('effect', 'slide'),
    ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
@endphp
<div class="{{ $classes }}" style="{{ $style }}">
    @if ($variant === 'lightbox')
        @if ($image)
            <a href="{{ e($image) }}" data-lightbox="pb" class="block"><img src="{{ e($image) }}" alt="{{ e($setting('caption','')) }}" class="w-full rounded-lg object-cover"></a>
            @if ($setting('caption',''))<p class="pb-muted mt-1 text-xs">{{ e($setting('caption')) }}</p>@endif
        @else
            <p class="pb-muted text-xs">تصویری انتخاب نشده است.</p>
        @endif
    @elseif ($photos === [])
        <p class="pb-muted text-xs">تصاویری برای نمایش وجود ندارد.</p>
    @elseif (in_array($variant, ['carousel', 'slides'], true))
        <div class="swiper pb-gallery-slider" x-init="new Swiper($el, {{ $swiperConfig }})">
            <div class="swiper-wrapper">
                @foreach ($photos as $index => $photo)
                    <div class="swiper-slide">
                        <img src="{{ e($photo) }}" alt="{{ e($titles[$index] ?? '') }}" class="h-full w-full rounded-lg object-cover" style="height:{{ (int) $setting('height',280) }}px">
                    </div>
                @endforeach
            </div>
            @if ($setting('pagination',true))<div class="swiper-pagination"></div>@endif
            @if ($setting('arrows',true))<div class="swiper-button-prev"></div><div class="swiper-button-next"></div>@endif
        </div>
    @else
        <div class="pb-grid" style="--pb-columns:{{ max(1,min(6,(int) $setting('columns',4))) }}">
            @foreach ($photos as $index => $photo)
                <a href="{{ e($photo) }}" @if($setting('lightbox',true)) data-lightbox="pb" @endif class="group block overflow-hidden pb-card">
                    <img src="{{ e($photo) }}" alt="{{ e($titles[$index] ?? '') }}" class="h-40 w-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @if ($setting('show_title',true) && isset($titles[$index]))<p class="mt-2 truncate text-xs font-bold">{{ e($titles[$index]) }}</p>@endif
                </a>
            @endforeach
        </div>
    @endif
</div>
