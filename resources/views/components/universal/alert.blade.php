@php
    $tones = ['info' => 'bg-sky-50 text-sky-900 dark:bg-sky-500/10 dark:text-sky-200', 'success' => 'bg-emerald-50 text-emerald-900 dark:bg-emerald-500/10 dark:text-emerald-200', 'warning' => 'bg-amber-50 text-amber-900 dark:bg-amber-500/10 dark:text-amber-200', 'danger' => 'bg-rose-50 text-rose-900 dark:bg-rose-500/10 dark:text-rose-200'];
    $tone = $tones[$setting('type','info')] ?? $tones['info'];
    $title = (string) $setting('title', 'توجه');
    $body = app(\Elearnac\PageBuilder\Services\RichText::class)->clean((string) $setting('body', ''));
    $percent = (int) $setting('total',10) > 0 ? round(((int) $setting('score',0) / (int) $setting('total',10)) * 100) : 0;
    $passed = $percent >= (int) $setting('passing',60);
@endphp
<div class="{{ $classes }}" style="{{ $style }}">
    @switch($variant)
        @case('result')
            <div class="pb-card text-center">
                <div class="text-4xl font-black {{ $passed ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">{{ $percent }}٪</div>
                <p class="mt-1 text-sm font-extrabold {{ $passed ? 'text-emerald-700 dark:text-emerald-300' : 'text-rose-700 dark:text-rose-300' }}">{{ $setting('message') ?: ($passed ? 'قبول شدید' : 'قبول نشدید') }}</p>
                <p class="pb-muted mt-2 text-xs">نمره {{ (int) $setting('score',0) }} از {{ (int) $setting('total',10) }} — حد قبولی {{ (int) $setting('passing',60) }}٪</p>
            </div>
            @break
        @case('callout')
            <div class="pb-card flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div><h3 class="pb-title text-base">{{ e($title) }}</h3><p class="pb-muted mt-1 text-xs">{!! $body !!}</p></div>
                <a href="{{ e($setting('link','#')) }}" class="pb-button">{{ e($setting('button','ادامه مطلب')) }}</a>
            </div>
            @break
        @case('banner')
            @php
                $bannerImage = $image ?: ($images[0] ?? null);
                $bannerStyle = $bannerImage ? "background-image:url('".e($bannerImage)."');background-size:cover;background-position:center" : '';
            @endphp
            <a href="{{ e($setting('link','#')) }}" class="relative block overflow-hidden pb-card" style="{{ $bannerStyle }}">
                <div class="bg-gradient-to-l from-black/70 to-transparent p-6 text-white">
                    <h3 class="text-lg font-extrabold">{{ e($title) }}</h3>
                    <p class="mt-1 text-xs opacity-90">{!! $body !!}</p>
                </div>
            </a>
            @break
        @case('notice')
            <div class="pb-card flex items-start gap-3">
                <i class="far fa-bell mt-1 text-brand-500"></i>
                <div class="flex-1"><h3 class="text-sm font-extrabold">{{ e($title) }}</h3><p class="pb-muted mt-1 text-xs">{!! $body !!}</p></div>
            </div>
            @break
        @default
            <div x-data="{ show: true }" x-show="show" class="rounded-lg p-4 {{ $tone }}">
                <div class="flex items-start gap-2">
                    <i class="far fa-info-circle mt-1"></i>
                    <div class="flex-1"><p class="text-sm font-extrabold">{{ e($title) }}</p><div class="mt-1 text-xs">{!! $body !!}</div></div>
                    @if ($setting('dismissible', true))<button type="button" @click="show=false" aria-label="بستن" class="text-lg leading-none">×</button>@endif
                </div>
            </div>
    @endswitch
</div>
