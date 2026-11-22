@php
    $links = (array) $setting('items', []);
    $horizontal = $setting('orientation', 'horizontal') === 'horizontal';
@endphp
<div class="{{ $classes }} {{ $setting('sticky') ? 'sticky top-0 z-40' : '' }}" style="{{ $style }}">
    @switch($variant)
        @case('toc')
            <nav class="pb-card" aria-label="فهرست مطالب">
                @if ($setting('title',''))<p class="mb-2 text-xs font-extrabold">{{ e($setting('title')) }}</p>@endif
                <ol class="space-y-1 text-xs pb-muted">
                    @forelse ($links as $link)
                        <li><a href="#{{ e($link['url'] ?? $link['title'] ?? '') }}" class="hover:text-brand-600">{{ e($link['title'] ?? '') }}</a></li>
                    @empty
                        <li>عنوانی یافت نشد.</li>
                    @endforelse
                </ol>
            </nav>
            @break
        @case('post-navigation')
            <nav class="grid gap-2 sm:grid-cols-2" aria-label="ناوبری نوشته">
                <a href="{{ e($setting('previous_url','#')) }}" class="pb-card block text-xs"><span class="pb-muted block">{{ e($setting('previous_label','نوشته قبلی')) }}</span><b>{{ e($setting('previous_title','')) }}</b></a>
                <a href="{{ e($setting('next_url','#')) }}" class="pb-card block text-xs"><span class="pb-muted block">{{ e($setting('next_label','نوشته بعدی')) }}</span><b>{{ e($setting('next_title','')) }}</b></a>
            </nav>
            @break
        @default
            <nav class="{{ $horizontal ? 'flex flex-wrap items-center gap-1' : 'flex flex-col gap-1' }}" aria-label="منوی صفحه">
                @forelse ($links as $link)
                    <a href="{{ e($link['url'] ?? '#') }}" class="rounded-lg px-3 py-2 text-xs font-bold text-slate-600 transition-colors hover:bg-brand-500 hover:text-white dark:text-slate-300">
                        @if (! empty($link['icon']))<i class="{{ e($link['icon']) }} ms-1"></i>@endif
                        {{ e($link['title'] ?? '') }}
                    </a>
                @empty
                    <p class="pb-muted text-xs">لینکی وجود ندارد.</p>
                @endforelse
            </nav>
    @endswitch
</div>
