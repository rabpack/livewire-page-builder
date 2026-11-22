<div class="{{ app(\Elearnac\PageBuilder\ComponentRegistry::class)->classes('section') }}" x-data x-init="
    if (window.Swiper) new Swiper($refs.el, {
        effect: @js($this->setting('style','default') === 'fade' ? 'fade' : ($this->setting('style') === 'cards' ? 'cards' : 'slide')),
        loop: true,
        autoplay: {{ $this->setting('autoplay',true) ? 'true' : 'false' }},
        slidesPerView: 1,
        breakpoints: { 640: { slidesPerView: {{ explode(',',(string)$this->setting('breakpoints','1,2,3'))[1] ?? 2 }} }, 1024: { slidesPerView: {{ explode(',',(string)$this->setting('breakpoints','1,2,3'))[2] ?? 3 }} } }
    });
">
    <div class="swiper" x-ref="el" wire:ignore.self>
        <div class="swiper-wrapper">
            @foreach ($images as $image)
                <div class="swiper-slide"><img src="{{ $image }}" alt="" class="w-full object-cover rounded-xl" style="height:{{ (int)$this->setting('height',360) }}px"></div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>
