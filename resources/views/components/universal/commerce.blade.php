@php
    $columns = max(1, min(4, (int) $setting('columns', 3)));
    $plans = (array) $setting('plans', []);
    if ($plans === [] && $setting('plan', '') !== '') {
        $plans = [[
            'name' => $setting('plan'),
            'price' => $setting('price', ''),
            'period' => $setting('period', ''),
            'features' => collect((array) $setting('features', []))->pluck('title')->filter()->implode("\n"),
            'featured' => (bool) $setting('highlight', false),
            'link' => $setting('link', '#'),
        ]];
    }
    $carousel = $type === 'product-carousel';
@endphp
<div class="{{ $classes }}" style="{{ $style }};--pb-columns:{{ $columns }}">
    @switch($variant)
        @case('pricing-table')
            <div class="pb-grid">
                @forelse ($plans as $plan)
                    <article class="pb-card {{ ! empty($plan['featured']) ? 'ring-2 ring-brand-500' : '' }}">
                        @if (! empty($plan['featured']))<span class="pb-chip mb-2">پیشنهاد ویژه</span>@endif
                        <h3 class="text-sm font-extrabold">{{ e($plan['name'] ?? '') }}</h3>
                        <p class="mt-2 text-xl font-black text-brand-600 dark:text-brand-300">{{ e($plan['price'] ?? '') }} <small class="text-[10px] pb-muted">{{ e($plan['period'] ?? '') }}</small></p>
                        <div class="pb-muted mt-3 whitespace-pre-line text-xs">{!! app(\Elearnac\PageBuilder\Services\RichText::class)->clean($plan['features'] ?? '') !!}</div>
                        <a href="{{ e($plan['link'] ?? '#') }}" class="pb-button mt-4 w-full">{{ e($setting('button','خرید پلن')) }}</a>
                    </article>
                @empty
                    <p class="pb-muted text-xs">پلنی تعریف نشده است.</p>
                @endforelse
            </div>
            @break
        @case('products')
        @case('product')
        @case('wishlist')
            @if ($items->isEmpty())
                <p class="pb-card pb-muted text-xs">محصولی برای نمایش وجود ندارد.</p>
            @elseif ($carousel)
                <div class="swiper" x-init="new Swiper($el, {{ json_encode(['slidesPerView' => max(1, min(6, (int) $setting('slides_desktop', 3))), 'spaceBetween' => (int) $setting('gap', 16), 'loop' => (bool) $setting('loop', false), 'autoplay' => $setting('autoplay', false) ? ['delay' => 3500] : false], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) }})">
                    <div class="swiper-wrapper">
                        @foreach ($items as $item)
                            <div class="swiper-slide h-auto">
                                @include('page-builder::components.universal.partials.item-card', ['item' => $item, 'settings' => $settings, 'variant' => 'product', 'setting' => $setting])
                            </div>
                        @endforeach
                    </div>
                    @if ($setting('pagination',true))<div class="swiper-pagination"></div>@endif
                    @if ($setting('arrows',true))<div class="swiper-button-prev"></div><div class="swiper-button-next"></div>@endif
                </div>
            @else
                <div class="pb-grid">
                    @foreach ($items as $item)
                        @include('page-builder::components.universal.partials.item-card', ['item' => $item, 'settings' => $settings, 'variant' => 'product', 'setting' => $setting])
                    @endforeach
                </div>
            @endif
            @break
        @case('stock')
            @php($status = $setting('status','in'))
            <span class="pb-chip" style="background:{{ $status === 'in' ? '#dcfce7' : ($status === 'low' ? '#fef3c7' : '#fee2e2') }};color:{{ $status === 'in' ? '#166534' : ($status === 'low' ? '#92400e' : '#991b1b') }}">{{ e($setting('label') ?: ($status === 'in' ? 'موجود' : ($status === 'low' ? 'کم موجود' : 'ناموجود'))) }}</span>
            @break
        @case('cart')
        @case('mini-cart')
            <div class="pb-card">
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold">{{ e($setting('title', $variant === 'cart' ? 'سبد خرید' : 'سبد خرید من')) }}</h3>
                    @if ($setting('show_count',true))<span class="pb-chip">۰ محصول</span>@endif
                </div>
                <p class="pb-muted text-xs">سبد خرید شما خالی است.</p>
                @if ($variant === 'cart')<a href="{{ e($setting('checkout_link','/checkout')) }}" class="pb-button mt-3">رفتن به پرداخت</a>@endif
            </div>
            @break
        @case('checkout')
            <div class="pb-card">
                <h3 class="mb-3 text-sm font-extrabold">{{ e($setting('title','خلاصه سفارش')) }}</h3>
                <dl class="space-y-2 text-xs">
                    <div class="flex justify-between"><dt class="pb-muted">جمع محصولات</dt><dd>۰ تومان</dd></div>
                    @if ($setting('show_shipping',true))<div class="flex justify-between"><dt class="pb-muted">حمل و نقل</dt><dd>رایگان</dd></div>@endif
                    @if ($setting('show_tax',true))<div class="flex justify-between"><dt class="pb-muted">مالیات</dt><dd>۰ تومان</dd></div>@endif
                    <div class="flex justify-between border-t border-slate-200 pt-2 font-extrabold dark:border-slate-700"><dt>مجموع</dt><dd>۰ تومان</dd></div>
                </dl>
                <button type="button" class="pb-button mt-4 w-full">{{ e($setting('button','پرداخت')) }}</button>
            </div>
            @break
        @case('reviews')
            <div class="space-y-2">
                @forelse ($items as $item)
                    <div class="pb-card text-xs"><p class="font-bold">{{ e($item['title'] ?? '') }}</p><p class="pb-muted mt-1">{{ e($item['text'] ?? '') }}</p></div>
                @empty
                    <p class="pb-card pb-muted text-xs">نظری وجود ندارد.</p>
                @endforelse
                @if ($setting('show_form'))<textarea class="pb-input mt-2" rows="3" placeholder="نظر خود را بنویسید"></textarea>@endif
            </div>
            @break
        @case('reviews-summary')
            <div class="pb-card">
                <div class="flex items-center gap-3">
                    <span class="text-3xl font-black text-amber-500">{{ e($setting('rating',4)) }}</span>
                    <div><div class="text-amber-500" dir="ltr">@for($i=1;$i<=5;$i++)<i class="{{ $i <= (int) $setting('rating',4) ? 'fas' : 'far' }} fa-star"></i>@endfor</div><p class="pb-muted text-[10px]">{{ (int) $setting('count',0) }} نظر</p></div>
                </div>
                @if ($setting('show_bars',true))
                    <div class="mt-3 space-y-1">@for($i=5;$i>=1;$i--)<div class="flex items-center gap-2 text-[10px]"><span>{{ $i }}★</span><div class="h-1.5 flex-1 rounded-full bg-slate-200 dark:bg-slate-700"><div class="h-full rounded-full bg-amber-500" style="width:{{ $i === (int) $setting('rating',4) ? 70 : 12 }}%"></div></div></div>@endfor</div>
                @endif
            </div>
            @break
        @case('price')
            <div class="flex items-center gap-2">
                @if ($setting('old_amount',''))<del class="pb-muted text-sm">{{ e($setting('old_amount')) }}</del>@endif
                <span class="text-2xl font-black text-brand-600 dark:text-brand-300">{{ e($setting('amount','')) }}</span>
                <span class="pb-muted text-xs">{{ e($setting('currency','تومان')) }}</span>
            </div>
            @break
        @case('payment')
        @case('add-to-cart')
            <button type="button" class="pb-button {{ $setting('block') ? 'w-full' : '' }}">
                @if ($variant === 'payment')<i class="far fa-credit-card"></i>@else<i class="far fa-cart-plus"></i>@endif
                <span>{{ e($setting('label', $variant === 'payment' ? 'پرداخت' : 'افزودن به سبد')) }}</span>
            </button>
            @break
        @default
            <div class="pb-card pb-muted text-xs">محصولی یافت نشد.</div>
    @endswitch
</div>
