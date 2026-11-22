<div class="{{ app(\Elearnac\PageBuilder\ComponentRegistry::class)->classes('section') }}" x-data x-init="
    if (window.Swiper) new Swiper($refs.el, { loop:true, slidesPerView:1, breakpoints:{640:{slidesPerView:2},1024:{slidesPerView:3}} });
">
    <div class="swiper" x-ref="el" wire:ignore.self>
        <div class="swiper-wrapper">
            @forelse ($items as $item)
                <div class="swiper-slide">
                    <div class="p-4" style="background:{{ $this->setting('background','#ffffff') }};border-radius:{{ (int)$this->setting('radius',16) }}px;" class="{{ $this->setting('card_classes','') }}">
                        @if ($this->setting('show_image',true)) <div class="mb-2 h-28 rounded bg-slate-100"></div> @endif
                        @if ($this->setting('show_title',true)) <h3 class="text-sm font-bold">{{ $item['title'] }}</h3> @endif
                    </div>
                </div>
            @empty
                <div class="swiper-slide text-sm text-slate-400">هیچ موردی یافت نشد.</div>
            @endforelse
        </div>
    </div>
</div>
