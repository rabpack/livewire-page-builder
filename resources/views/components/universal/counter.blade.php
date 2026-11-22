@php
    $value = (int) $setting('number', 0);
    $percent = max(0, min(100, (int) $setting('value', 0)));
    $rating = max(0, min(5, (int) $setting('value', 4)));
    $stats = (array) $setting('items', []);
@endphp
<div class="{{ $classes }}" style="{{ $style }}">
    @switch($variant)
        @case('progress')
            <div>
                @if ($setting('label',''))<div class="mb-2 flex justify-between text-xs font-bold"><span>{{ e($setting('label')) }}</span><span>{{ $percent }}٪</span></div>@endif
                <div class="h-{{ (int) $setting('height',10) }} w-full overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700" style="height:{{ (int) $setting('height',10) }}px">
                    <div class="h-full rounded-full bg-brand-500 {{ $setting('striped') ? 'bg-stripes' : '' }}" style="width:{{ $percent }}%"></div>
                </div>
            </div>
            @break
        @case('rating')
            <div class="flex items-center gap-2 text-sm">
                <span class="text-amber-500" dir="ltr">
                    @for($i=1;$i<=5;$i++)<i class="{{ $i <= $rating ? 'fas' : 'far' }} fa-star"></i>@endfor
                </span>
                <span class="font-bold">{{ $rating }}/5</span>
                @if ($setting('show_count',true))<span class="pb-muted text-xs">({{ (int) $setting('count',0) }} رأی)</span>@endif
            </div>
            @break
        @case('stats')
            <div class="pb-grid" style="--pb-columns:{{ max(1,min(6,(int) $setting('columns',4))) }}">
                @forelse ($stats as $item)
                    <div class="pb-card text-center">
                        @if ($item['icon'] ?? '')<i class="{{ e($item['icon']) }} text-xl text-brand-500"></i>@endif
                        <p class="mt-1 text-xl font-black">{{ e($item['value'] ?? '') }}</p>
                        <p class="pb-muted text-xs">{{ e($item['label'] ?? '') }}</p>
                    </div>
                @empty
                    <p class="pb-muted text-xs">آمارهایی وجود ندارد.</p>
                @endforelse
            </div>
            @break
        @default
            <div class="pb-card text-center">
                <div class="text-3xl font-black" x-data x-intersect.once="$el.textContent='{{ e($setting('prefix','')) }}{{ e(number_format($value)) }}{{ e($setting('suffix','')) }}'">
                    {{ e($setting('prefix','')) }}{{ e(number_format($value)) }}{{ e($setting('suffix','')) }}
                </div>
                <p class="pb-muted mt-1 text-xs">{{ e($setting('label','')) }}</p>
            </div>
    @endswitch
</div>
