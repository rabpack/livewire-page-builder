@php
    $configuredItems = (array) $setting('items', []);
    $items = $variant === 'testimonial-carousel' ? $items : $configuredItems;
    $clean = fn ($value) => app(\Elearnac\PageBuilder\Services\RichText::class)->clean((string) $value);
    $countdownTarget = (string) $setting('date', '');
    if ($countdownTarget === '') { $countdownTarget = now()->addDays(7)->toDateTimeString(); }
    $cardsConfig = json_encode([
        'effect' => (string) $setting('effect', 'cards'),
        'slidesPerView' => 1,
        'loop' => false,
        'autoplay' => $setting('autoplay', true) ? ['delay' => 4000, 'pauseOnMouseEnter' => true] : false,
        'cardsEffect' => ['perSlideRotate' => 4, 'perSlideOffset' => 6],
    ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
@endphp
<div class="{{ $classes }}" style="{{ $style }}">
    @switch($variant)
        @case('accordion')
            <div x-data="{ open: {{ $setting('open_first') ? 0 : -1 }} }" class="space-y-2">
                @forelse ($items as $index => $item)
                    <div class="pb-card overflow-hidden">
                        <button type="button" @click="open = {{ $setting('multiple') ? '(open === '.$index.' ? -1 : [...(Array.isArray(open) ? open : []), '.$index.'])' : '(open === '.$index.' ? -1 : '.$index.')' }}" class="flex w-full items-center justify-between p-3 text-start text-sm font-extrabold">
                            <span>{{ e($item['title'] ?? '') }}</span><i class="far fa-chevron-up text-xs" :class="Array.isArray(open) ? (open.includes({{ $index }}) ? '' : 'rotate-180') : (open === {{ $index }} ? '' : 'rotate-180')"></i>
                        </button>
                        <div x-show="Array.isArray(open) ? open.includes({{ $index }}) : open === {{ $index }}" x-collapse class="border-t border-slate-200 p-3 text-xs pb-muted dark:border-slate-700">{!! $clean($item['body'] ?? '') !!}</div>
                    </div>
                @empty
                    <p class="pb-muted text-xs">موردی اضافه نشده است.</p>
                @endforelse
            </div>
            @break
        @case('tabs')
            <div x-data="{ tab: 0 }">
                <div class="mb-3 flex flex-wrap gap-1 border-b border-slate-200 dark:border-slate-700">
                    @foreach ($items as $index => $item)
                        <button type="button" @click="tab={{ $index }}" :class="tab==={{ $index }} ? 'border-brand-500 text-brand-600 dark:text-brand-300' : 'border-transparent pb-muted'" class="border-b-2 px-3 py-2 text-xs font-extrabold">{{ e($item['title'] ?? '') }}</button>
                    @endforeach
                </div>
                @foreach ($items as $index => $item)
                    <div x-show="tab==={{ $index }}" class="text-xs leading-6">{!! $clean($item['body'] ?? '') !!}</div>
                @endforeach
            </div>
            @break
        @case('toggle')
            <div x-data="{ open: {{ $setting('open_first') ? 0 : -1 }} }" class="space-y-2">
                @foreach ($items as $index => $item)
                    <div class="pb-card">
                        <button type="button" @click="open = open === {{ $index }} ? -1 : {{ $index }}" class="flex w-full items-center justify-between p-3 text-xs font-extrabold"><span>{{ e($item['title'] ?? '') }}</span><i class="far fa-plus" :class="open === {{ $index }} ? 'fa-minus' : 'fa-plus'"></i></button>
                        <div x-show="open === {{ $index }}" x-collapse class="px-3 pb-3 text-xs pb-muted">{!! $clean($item['body'] ?? '') !!}</div>
                    </div>
                @endforeach
            </div>
            @break
        @case('testimonial')
            @php($avatar = $image ?: ($images[0] ?? null))
            <figure class="pb-card">
                <div class="mb-2 text-amber-500" dir="ltr">@for($i=1;$i<=5;$i++)<i class="{{ $i <= (int) $setting('rating',5) ? 'fas' : 'far' }} fa-star"></i>@endfor</div>
                <blockquote class="text-sm leading-7">{!! $clean($setting('body','')) !!}</blockquote>
                <figcaption class="mt-3 flex items-center gap-2">
                    @if ($avatar)<img src="{{ e($avatar) }}" alt="" class="size-9 rounded-full object-cover">@endif
                    <div><p class="text-xs font-extrabold">{{ e($setting('name','')) }}</p><p class="pb-muted text-[10px]">{{ e($setting('role','')) }}</p></div>
                </figcaption>
            </figure>
            @break
        @case('testimonial-carousel')
            <div class="swiper" x-init="new Swiper($el, {{ $cardsConfig }})" style="padding:8% 5%">
                <div class="swiper-wrapper">
                    @forelse ($items as $item)
                        <div class="swiper-slide">
                            <figure class="pb-card">
                                @if ($setting('image',true) && ($item['image'] ?? null))<img src="{{ e($item['image']) }}" alt="" class="mb-2 size-10 rounded-full object-cover">@endif
                                @if ($setting('rating',true))<div class="mb-1 text-amber-500" dir="ltr">@for($i=1;$i<=5;$i++)<i class="fas fa-star"></i>@endfor</div>@endif
                                <blockquote class="text-xs leading-6">{{ e($item['text'] ?? '') }}</blockquote>
                                <figcaption class="mt-2 text-[11px] font-extrabold">{{ e($item['title'] ?? '') }}</figcaption>
                            </figure>
                        </div>
                    @empty
                        <div class="swiper-slide"><p class="pb-muted text-xs">نظری وجود ندارد.</p></div>
                    @endforelse
                </div>
            </div>
            @break
        @case('modal')
            <div x-data="{ open:false }">
                <button type="button" @click="open=true" class="pb-button">{{ e($setting('button','باز کردن')) }}</button>
                <div x-show="open" x-cloak @click.self="open=false" class="fixed inset-0 z-[9000] grid place-items-center bg-slate-950/60 p-4" role="dialog" aria-modal="true">
                    <div class="w-full max-w-{{ $setting('size','md') === 'lg' ? '3xl' : ($setting('size','md') === 'sm' ? 'sm' : 'xl') }} pb-card">
                        <div class="mb-2 flex items-center justify-between"><h3 class="text-sm font-extrabold">{{ e($setting('title','')) }}</h3><button type="button" @click="open=false" aria-label="بستن">×</button></div>
                        <div class="text-xs leading-6">{!! $clean($setting('body','')) !!}</div>
                    </div>
                </div>
            </div>
            @break
        @case('offcanvas')
            <div x-data="{ open:false }">
                <button type="button" @click="open=true" class="pb-button">{{ e($setting('button','منو')) }}</button>
                <div x-show="open" x-cloak @click.self="open=false" class="fixed inset-0 z-[9000] bg-slate-950/60">
                    <aside class="h-full w-80 max-w-[85vw] overflow-y-auto bg-white p-4 shadow-xl dark:bg-slate-900 {{ $setting('position','right') === 'right' ? 'ms-auto' : '' }}">
                        <button type="button" @click="open=false" class="mb-3">×</button>
                        <div class="text-xs leading-6">{!! $clean($setting('body','')) !!}</div>
                    </aside>
                </div>
            </div>
            @break
        @case('tooltip')
            <span x-data="{ show:false }" class="relative inline-flex" @mouseenter="show=true" @mouseleave="show=false">
                <span class="pb-chip">{{ e($setting('label','')) }}</span>
                <span x-show="show" x-cloak class="absolute bottom-full start-1/2 z-10 mb-2 -translate-x-1/2 whitespace-nowrap rounded bg-slate-900 px-2 py-1 text-[10px] text-white">{{ e($setting('body','')) }}</span>
            </span>
            @break
        @case('flip')
            <div x-data="{ flipped:false }" class="[perspective:1000px]" @mouseenter="flipped=true" @mouseleave="flipped=false" @click="flipped=!flipped">
                <div class="relative h-64 w-full transition-transform duration-500 [transform-style:preserve-3d]" :class="flipped ? '[transform:rotateY(180deg)]' : ''">
                    <div class="absolute inset-0 pb-card [backface-visibility:hidden]">{!! $clean($setting('front','')) !!}</div>
                    <div class="absolute inset-0 pb-card [backface-visibility:hidden] [transform:rotateY(180deg)]">{!! $clean($setting('back','')) !!}</div>
                </div>
            </div>
            @break
        @case('content-toggle')
            <div x-data="{ side:'first' }">
                <div class="mb-3 flex gap-2"><button type="button" @click="side='first'" class="pb-chip" :class="side==='first' ? 'ring-2 ring-brand-500' : ''">{{ e($setting('first_label','')) }}</button><button type="button" @click="side='second'" class="pb-chip" :class="side==='second' ? 'ring-2 ring-brand-500' : ''">{{ e($setting('second_label','')) }}</button></div>
                <div x-show="side==='first'" class="text-xs leading-6">{!! $clean($setting('first','')) !!}</div>
                <div x-show="side==='second'" class="text-xs leading-6">{!! $clean($setting('second','')) !!}</div>
            </div>
            @break
        @case('countdown')
            <div x-data="{ days:0, hours:0, minutes:0, seconds:0, tick(){ const ms=Math.max(0,new Date(@js($countdownTarget)).getTime()-Date.now()); const s=Math.floor(ms/1000); this.days=Math.floor(s/86400); this.hours=Math.floor(s%86400/3600); this.minutes=Math.floor(s%3600/60); this.seconds=s%60; } }" x-init="tick(); window.setInterval(() => tick(), 1000)" class="pb-card text-center" dir="ltr">
                <p class="mb-2 text-xs font-bold pb-muted" dir="rtl">{{ e($setting('label','')) }}</p>
                <div class="flex justify-center gap-2 text-center text-lg font-black text-brand-600 dark:text-brand-300">
                    <span x-show="days>0"><b x-text="days"></b><small class="block text-[9px] pb-muted">day</small></span>
                    <span><b x-text="hours"></b><small class="block text-[9px] pb-muted">hr</small></span>
                    <span><b x-text="minutes"></b><small class="block text-[9px] pb-muted">min</small></span>
                    <span><b x-text="seconds"></b><small class="block text-[9px] pb-muted">sec</small></span>
                </div>
            </div>
            @break
        @default
            <p class="pb-muted text-xs">محتوای تعاملیای وجود ندارد.</p>
    @endswitch
</div>
