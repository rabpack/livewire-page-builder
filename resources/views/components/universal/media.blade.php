@php
    $url = (string) $setting('url', '');
    $poster = $image ?: ($images[0] ?? null);
    $videoId = null;
    if (str_contains($url, 'youtube.com/watch?v=')) { parse_str(parse_url($url, PHP_URL_QUERY) ?: '', $query); $videoId = $query['v'] ?? null; }
    if (str_contains($url, 'youtu.be/')) { $videoId = basename(parse_url($url, PHP_URL_PATH) ?: ''); }
    if (str_contains($url, 'vimeo.com/')) { $videoId = basename(parse_url($url, PHP_URL_PATH) ?: ''); }
    $before = $images[0] ?? null;
    $after = $images[1] ?? null;
@endphp
<div class="{{ $classes }}" style="{{ $style }}">
    @switch($variant)
        @case('youtube')
        @case('vimeo')
            @if ($videoId)
                <div class="relative overflow-hidden pb-card" style="aspect-ratio:16/9">
                    <iframe class="absolute inset-0 h-full w-full" src="https://{{ $variant === 'youtube' ? 'www.youtube-nocookie.com/embed' : 'player.vimeo.com/video' }}/{{ e($videoId) }}" title="{{ e($setting('caption','')) }}" allowfullscreen loading="lazy"></iframe>
                </div>
            @else
                <p class="pb-muted text-xs">لینک ویدیو معتبر نیست.</p>
            @endif
            @break
        @case('audio-list')
            <div class="space-y-2">
                @forelse ((array) $setting('items', []) as $track)
                    <div class="pb-card flex items-center gap-3">
                        <i class="far fa-music text-brand-500"></i>
                        <div class="flex-1 text-xs font-bold">{{ e($track['title'] ?? '') }}</div>
                        <audio controls src="{{ e($track['url'] ?? '') }}" class="h-8 max-w-[220px]"></audio>
                    </div>
                @empty
                    <p class="pb-muted text-xs">آهنگی وجود ندارد.</p>
                @endforelse
            </div>
            @break
        @case('audio')
            <div class="pb-card">
                @if ($setting('title',''))<p class="mb-2 text-sm font-bold">{{ e($setting('title')) }}</p>@endif
                <audio @if($setting('controls',true)) controls @endif src="{{ e($url) }}" class="w-full"></audio>
            </div>
            @break
        @case('compare')
            @if ($before && $after)
                <div class="relative overflow-hidden pb-card" style="height:{{ (int) $setting('height',360) }}px" x-data="{ pos: {{ (int) $setting('position',50) }} }">
                    <img src="{{ e($after) }}" alt="بعد" class="absolute inset-0 h-full w-full object-cover">
                    <div class="absolute inset-0 overflow-hidden" :style="'width:'+pos+'%'">
                        <img src="{{ e($before) }}" alt="قبل" class="h-full w-full object-cover" style="width:{{ (int) $setting('width',600) }}px;max-width:none">
                    </div>
                    <div class="absolute inset-y-0 w-0.5 bg-white shadow" :style="'inset-inline-start:'+pos+'%'"></div>
                    <input type="range" min="0" max="100" x-model.number="pos" class="absolute bottom-2 inset-inline-0 w-[92%] mx-auto" aria-label="جابجایی مقایسه">
                </div>
            @else
                <p class="pb-muted text-xs">دو تصویر برای مقایسه انتخاب کنید.</p>
            @endif
            @break
        @case('hotspot')
            <div class="relative pb-card overflow-hidden">
                @if ($image)<img src="{{ e($image) }}" alt="" class="w-full">@endif
                @foreach ((array) $setting('items', []) as $spot)
                    <button type="button" x-data="{ show:false }" @click="show=!show" class="absolute size-6 -translate-x-1/2 -translate-y-1/2 rounded-full bg-brand-500 text-white shadow" style="inset-inline-start:{{ e($spot['x'] ?? 50) }}%;top:{{ e($spot['y'] ?? 50) }}%" aria-label="{{ e($spot['title'] ?? '') }}">+</button>
                @endforeach
            </div>
            @break
        @case('lottie')
            <div class="pb-card grid min-h-48 place-items-center text-xs pb-muted" data-lottie-url="{{ e($url) }}">انیمیشن Lottie: {{ e($url) ?: 'لینکی وارد نشده است' }}</div>
            @break
        @case('player')
            @if ($items->isNotEmpty())
                @php($first = $items->first())
                <div class="pb-card overflow-hidden">
                    <video controls class="w-full" @if($poster) poster="{{ e($poster) }}" @endif src="{{ e($first['url'] ?? '#') }}"></video>
                    @if ($setting('show_playlist',true))
                        <div class="divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach ($items as $lesson)
                                <div class="flex items-center gap-2 p-2 text-xs"><i class="far fa-play-circle text-brand-500"></i><span class="flex-1">{{ e($lesson['title']) }}</span></div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <p class="pb-muted text-xs">درسی برای نمایش وجود ندارد.</p>
            @endif
            @break
        @default
            <div class="pb-card overflow-hidden">
                @if ($url)
                    <video controls class="w-full" @if($setting('autoplay')) autoplay @endif @if($setting('loop')) loop @endif @if($setting('muted')) muted @endif @if($poster) poster="{{ e($poster) }}" @endif style="aspect-ratio:{{ e($setting('ratio','16/9')) }}">
                        <source src="{{ e($url) }}">
                    </video>
                @elseif ($poster)
                    <img src="{{ e($poster) }}" alt="{{ e($setting('alt','')) }}" class="w-full object-cover">
                @else
                    <p class="pb-muted p-4 text-xs">ویدیویی انتخاب نشده است.</p>
                @endif
                @if ($setting('caption',''))<p class="pb-muted p-3 text-xs">{{ e($setting('caption')) }}</p>@endif
            </div>
    @endswitch
</div>
