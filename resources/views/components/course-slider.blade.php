<div class="relative" x-data="{}">
    @if (count($items))
        <div class="swiper" id="{{ $uid }}">
            <div class="swiper-wrapper">
                @foreach ($items as $item)
                    <div class="swiper-slide">
                        <a href="{{ $item['url'] }}" class="block h-full {{ $this->setting('card_classes','') }}"
                           style="background:{{ $this->setting('background','#ffffff') }};border-radius:{{ (int) $this->setting('radius',16) }}px;">
                            @if ($this->setting('show_image', true) && $item['image'])
                                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="h-36 w-full rounded-t-xl object-cover">
                            @endif
                            <div class="p-3">
                                @if ($this->setting('show_title', true))<h3 class="line-clamp-2 text-sm font-extrabold text-slate-800 dark:text-white">{{ $item['title'] }}</h3>@endif
                                <div class="mt-2 flex items-center justify-between">
                                    @if ($this->setting('show_price', true))<span class="text-xs font-bold text-brand-600">{{ number_format((float) ($item['price'] ?? 0)) }} تومان</span>@endif
                                    @if ($this->setting('show_rating', true) && $item['rating'])<span class="text-xs text-amber-500">★ {{ $item['rating'] }}</span>@endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            @if ($this->setting('pagination', true))<div class="swiper-pagination !relative mt-3"></div>@endif
            @if ($this->setting('arrows', true))<div class="swiper-button-next"></div><div class="swiper-button-prev"></div>@endif
        </div>
        <script>
            (function () {
                const init = () => {
                    const el = document.getElementById('{{ $uid }}');
                    if (! el || ! window.Swiper || el.dataset.pbReady) return;
                    el.dataset.pbReady = '1';
                    new Swiper(el, {
                        slidesPerView: 1, spaceBetween: 12,
                        breakpoints: {640:{slidesPerView:2},1024:{slidesPerView:3},1280:{slidesPerView:4}},
                        autoplay: @json($this->setting('autoplay', false) ? ['delay'=>3500] : false),
                        navigation: @json($this->setting('arrows', true)),
                        pagination: @json($this->setting('pagination', true) ? ['el'=>'.swiper-pagination'] : false),
                    });
                };
                init(); document.addEventListener('livewire:initialized', init); document.addEventListener('livewire:navigated', init);
            })();
        </script>
    @else
        <div class="grid min-h-40 place-items-center rounded-xl border border-dashed border-slate-300 text-xs text-slate-400">دورهای یافت نشد.</div>
    @endif
</div>
