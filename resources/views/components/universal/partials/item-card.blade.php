@php
    $title = (string) ($item['title'] ?? '');
    $text = (string) ($item['text'] ?? '');
    $meta = (string) ($item['meta'] ?? '');
    $url = (string) ($item['url'] ?? '#');
    $thumb = $item['image'] ?? null;
    $showImage = (bool) ($settings['show_image'] ?? true);
    $showTitle = (bool) ($settings['show_title'] ?? true);
    $showText = (bool) ($settings['show_text'] ?? true);
    $showMeta = (bool) ($settings['show_meta'] ?? true);
    $isCompact = in_array($variant, ['category', 'comments', 'lessons', 'exams'], true);
@endphp
<article class="{{ $isCompact ? 'flex items-center gap-2 rounded-lg border border-slate-200 p-2 text-xs dark:border-slate-700' : 'pb-card overflow-hidden h-full' }}">
    @if ($showImage && $thumb)
        <img src="{{ e($thumb) }}" alt="{{ e($title) }}" class="{{ $isCompact ? 'size-10 rounded object-cover' : 'h-36 w-full object-cover' }}" loading="lazy">
    @endif
    <div class="{{ $isCompact ? 'min-w-0 flex-1' : 'mt-3' }}">
        @if ($showTitle)
            <h3 class="{{ $isCompact ? 'truncate font-bold' : 'text-sm font-extrabold' }}">
                @if ($url !== '#')<a href="{{ e($url) }}" class="hover:text-brand-600">{{ e($title) }}</a>@else{{ e($title) }}@endif
            </h3>
        @endif
        @if ($showText && $text !== '')
            <p class="pb-muted mt-1 line-clamp-2 text-xs">{!! app(\Elearnac\PageBuilder\Services\RichText::class)->clean($text) !!}</p>
        @endif
        @if ($showMeta && $meta !== '')<p class="pb-muted mt-1 text-[10px]">{{ e($meta) }}</p>@endif
        @if (in_array($variant, ['course','product','loop','portfolio','search'], true) && ($settings['show_price'] ?? false))
            <span class="mt-2 inline-block text-sm font-black text-brand-600 dark:text-brand-300">{{ e($meta ?: 'رایگان') }}</span>
        @endif
        @if (! empty($settings['show_cart']))
            <button type="button" class="pb-button mt-3 w-full text-[11px]">{{ e($variant === 'product' ? 'افزودن به سبد' : 'مشاهده') }}</button>
        @endif
    </div>
</article>
