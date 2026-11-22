@php
    $imageUrl = $image ?: ($images[0] ?? ($items->first()['image'] ?? null));
@endphp
<div class="{{ $classes }}" style="{{ $style }}">
    @switch($variant)
        @case('icon-box')
            <article class="pb-card">
                @if ($setting('icon'))<i class="{{ e($setting('icon')) }} text-xl text-brand-500"></i>@endif
                <h3 class="mt-2 text-sm font-extrabold">{{ e($setting('title','')) }}</h3>
                @if ($setting('body',''))<p class="pb-muted mt-1 text-xs">{{ e($setting('body')) }}</p>@endif
                @if ($setting('link','#'))<a href="{{ e($setting('link','#')) }}" class="pb-link mt-3 inline-block text-xs">بیشتر بدانید</a>@endif
            </article>
            @break
        @case('image-box')
            <article class="pb-card overflow-hidden">
                @if ($imageUrl)<img src="{{ e($imageUrl) }}" alt="{{ e($setting('alt',$setting('title',''))) }}" class="h-44 w-full object-cover">@endif
                <div class="p-4">
                    <h3 class="pb-title text-base">{{ e($setting('title','')) }}</h3>
                    @if ($setting('body',''))<p class="pb-muted mt-1 text-xs">{{ e($setting('body')) }}</p>@endif
                    @if ($setting('link','#'))<a href="{{ e($setting('link','#')) }}" class="pb-link mt-3 inline-block text-xs">بیشتر بدانید</a>@endif
                </div>
            </article>
            @break
        @case('featured-image')
            <img src="{{ e($imageUrl) }}" alt="{{ e($setting('alt','')) }}" class="w-full object-{{ $setting('fit','cover') }}" style="height:{{ (int) $setting('height',320) }}px">
            @break
        @case('badge')
            <span class="pb-chip" style="background:{{ e($setting('background','#e11d48')) }};color:#fff">{{ e($setting('text','تخفیف ویژه')) }}</span>
            @break
        @case('stock')
            @php($status = $setting('status','in'))
            <span class="pb-chip" style="background:{{ $status === 'in' ? '#dcfce7' : ($status === 'low' ? '#fef3c7' : '#fee2e2') }};color:{{ $status === 'in' ? '#166534' : ($status === 'low' ? '#92400e' : '#991b1b') }}">{{ e($setting('label') ?: ($status === 'in' ? 'موجود' : ($status === 'low' ? 'کم موجود' : 'ناموجود'))) }}</span>
            @break
        @case('icon')
            @if ($setting('link'))
                <a href="{{ e($setting('link')) }}" class="inline-block"><i class="{{ e($setting('icon','far fa-star')) }}"></i></a>
            @else
                <i class="{{ e($setting('icon','far fa-star')) }}"></i>
            @endif
            @break
        @default
            @if ($imageUrl)
                <img src="{{ e($imageUrl) }}" alt="{{ e($setting('alt','')) }}" class="w-full rounded-lg object-cover">
            @else
                <div class="grid h-48 place-items-center rounded-lg border border-dashed border-slate-300 text-xs text-slate-400 dark:border-slate-700">تصویری انتخاب نشده است</div>
            @endif
    @endswitch
</div>
