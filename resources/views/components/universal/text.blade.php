@php
    $html = app(\Elearnac\PageBuilder\Services\RichText::class)->clean((string) $setting('body', ''));
    $tag = in_array($setting('tag','p'), ['p','div','blockquote','pre'], true) ? $setting('tag','p') : 'p';
    $tag = $variant === 'quote' ? 'blockquote' : $tag;
    $siteName = config('app.name', 'سایت');
    $replacements = [
        'site_name' => $siteName,
        'page_title' => (string) data_get(request()->route()?->parameter('slug'), 'title', $siteName),
        'user_name' => auth()->user()?->name ?? ' مهمان',
        'date' => verta()->format('Y/m/d'),
    ];
@endphp
<div class="{{ $classes }}" style="{{ $style }}">
    @switch($variant)
        @case('dynamic-tag')
            @php($tagKey = (string) $setting('tag','site_name'))
            <span>{{ e($setting('prefix','')) }}{{ e($replacements[$tagKey] ?? $tagKey) }}{{ e($setting('suffix','')) }}</span>
            @break
        @case('code')
            <pre class="overflow-x-auto rounded-lg bg-slate-900 p-4 text-xs text-slate-100" dir="ltr">{{ $setting('body','') }}</pre>
            @break
        @case('shortcode')
            <p class="pb-muted">{{ e($setting('body','شورتکدی برای نمایش وجود ندارد.')) }}</p>
            @break
        @case('html')
        @case('post-content')
            <div class="pb-rich">{!! $html !!}</div>
            @break
        @case('excerpt')
            <p class="pb-muted">{{ \Illuminate\Support\Str::limit(strip_tags($html), max(20,(int) $setting('limit',220))) }}</p>
            @break
        @case('quote')
            <blockquote class="border-s-4 border-brand-500 pe-4 text-lg italic">{!! $html !!}</blockquote>
            @if ($setting('author','')) <p class="pb-muted mt-2 text-xs">{{ e($setting('author')) }}</p> @endif
            @break
        @default
            <{!! $tag !!} class="pb-rich">{!! $html !!}</{!! $tag !!}>
    @endswitch
</div>
