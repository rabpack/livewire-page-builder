<div class="relative" x-data="{}">
    @if (count($images))
        <div class="swiper" id="{{ $uid }}" style="height:{{ (int) $this->setting('height', 360) }}px;border-radius:{{ (int) $this->setting('radius', 16) }}px;overflow:hidden;">
            <div class="swiper-wrapper">
                @foreach ($images as $url)
                    <div class="swiper-slide">
                        <img src="{{ $url }}" alt="تصویر اسلایدر" class="h-full w-full object-{{ $this->setting('image_fit', 'cover') }}">
                    </div>
                @endforeach
            </div>
            @if ($this->setting('pagination', true))<div class="swiper-pagination"></div>@endif
            @if ($this->setting('arrows', true))
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            @endif
        </div>
        <script>
            (function () {
                const init = () => {
                    const el = document.getElementById('{{ $uid }}');
                    if (! el || ! window.Swiper || el.dataset.pbReady) return;
                    el.dataset.pbReady = '1';
                    new Swiper(el, @json($config));
                };
                init();
                document.addEventListener('livewire:initialized', init);
                document.addEventListener('livewire:navigated', init);
            })();
        </script>
    @else
        <div class="grid min-h-40 place-items-center rounded-xl border border-dashed border-slate-300 text-xs text-slate-400">
            لطفاً از مدارک سایت، تصاویر اسلایدر را انتخاب کنید.
        </div>
    @endif
</div>
