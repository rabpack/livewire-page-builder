@php
    $avatar = $image ?: ($images[0] ?? null);
    $name = (string) ($setting('name', '') ?: (auth()->user()?->name ?? 'کاربر'));
    $empty = (string) $setting('empty', 'موردی برای نمایش وجود ندارد.');
    $columns = max(1, min(4, (int) $setting('columns', 3)));
@endphp
<div class="{{ $classes }}" style="{{ $style }};--pb-columns:{{ $columns }}">
    @switch($variant)
        @case('profile')
            <div class="pb-card text-center">
                @if ($avatar)<img src="{{ e($avatar) }}" alt="{{ e($name) }}" class="mx-auto size-20 rounded-full object-cover">@endif
                <h3 class="mt-2 text-base font-extrabold">{{ e($name) }}</h3>
                @if ($setting('bio',''))<p class="pb-muted mt-1 text-xs">{{ e($setting('bio')) }}</p>@endif
                @if ($setting('show_stats',true))
                    <div class="mt-3 grid grid-cols-2 gap-2 text-xs"><div class="rounded-lg bg-slate-100 p-2 dark:bg-slate-800"><b>{{ $items->count() }}</b><span class="pb-muted block">دوره</span></div><div class="rounded-lg bg-slate-100 p-2 dark:bg-slate-800"><b>۰</b><span class="pb-muted block">آزمون</span></div></div>
                @endif
            </div>
            @break
        @case('teacher')
            @if ($items->isEmpty())
                <p class="pb-card pb-muted text-xs">{{ e($empty) }}</p>
            @else
                <div class="pb-grid">
                    @foreach ($items as $item)
                        <article class="pb-card text-center">
                            @if ($item['image'])<img src="{{ e($item['image']) }}" alt="{{ e($item['title']) }}" class="mx-auto size-16 rounded-full object-cover">@endif
                            <h3 class="mt-2 text-sm font-extrabold">{{ e($item['title']) }}</h3>
                            <p class="pb-muted text-[11px]">{{ e($setting('role','مدرس')) }}</p>
                            @if ($setting('social',true))<div class="mt-2 flex justify-center gap-2 text-brand-500"><i class="fab fa-instagram"></i><i class="fab fa-telegram-plane"></i></div>@endif
                        </article>
                    @endforeach
                </div>
            @endif
            @break
        @case('wallet')
        @case('subscription')
        @case('notifications')
        @case('messages')
        @case('bookings')
            <div class="pb-card">
                <h3 class="text-sm font-extrabold">{{ e($setting('title', ['wallet' => 'کیف پول من', 'subscription' => 'اشتراکها', 'notifications' => 'اعلانها', 'messages' => 'پیامها', 'bookings' => 'رزروها'][$variant] ?? 'اطلاعات کاربر')) }}</h3>
                @if ($variant === 'wallet')
                    <p class="mt-2 text-2xl font-black text-brand-600 dark:text-brand-300">۰ <small class="text-xs pb-muted">تومان</small></p>
                    @if ($setting('show_charge',true))<button type="button" class="pb-button mt-3">شارژ کیف پول</button>@endif
                @elseif ($items->isEmpty())
                    <p class="pb-muted mt-2 text-xs">{{ e($empty) }}</p>
                @else
                    <ul class="mt-2 divide-y divide-slate-200 text-xs dark:divide-slate-700">
                        @foreach ($items as $item)
                            <li class="flex items-center justify-between gap-2 py-2">
                                <span class="min-w-0 truncate">{{ e($item['title']) }}</span>
                                @if ($setting('show_status',true))<span class="pb-chip">فعال</span>@endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            @break
        @case('certificate')
            <article class="pb-card border-y-4 border-brand-500 text-center">
                <p class="text-xs pb-muted">گواهینامه پایان دوره</p>
                <h3 class="mt-2 text-lg font-black">{{ e($setting('name','')) }}</h3>
                <p class="mt-1 text-xs">{{ e($setting('course','')) }}</p>
                <p class="mt-3 text-[10px] pb-muted" dir="ltr">{{ e($setting('code','')) }} — {{ e($setting('date','')) }}</p>
            </article>
            @break
        @default
            @if ($items->isEmpty())
                <p class="pb-card pb-muted text-xs">{{ e($empty) }}</p>
            @else
                <div class="pb-grid">
                    @foreach ($items as $item)
                        @include('page-builder::components.universal.partials.item-card', ['item' => $item, 'settings' => $settings, 'variant' => $variant, 'setting' => $setting])
                    @endforeach
                </div>
            @endif
    @endswitch
</div>
