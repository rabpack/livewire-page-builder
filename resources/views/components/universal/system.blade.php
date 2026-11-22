@php
    $allowed = auth()->check();
    $condition = (string) $setting('condition', 'always');
    $visible = $condition === 'always' || ($condition === 'logged_in' && $allowed) || ($condition === 'guest' && ! $allowed);
    $body = app(\Elearnac\PageBuilder\Services\RichText::class)->clean((string) $setting('body', ''));
    $css = (string) $setting('css', '');
    $css = preg_replace('/[{};][^}]*url\s*\([^)]*\)/i', '', $css) ?? '';
    $css = str_replace(['<', '>', '</style'], '', $css);
@endphp
<div class="{{ $classes }}" style="{{ $style }}">
    @switch($variant)
        @case('custom-css')
            @if (trim($css) !== '')
                <style data-pb-scope="{{ e($setting('scope','pb-custom')) }}">{!! $css !!}</style>
            @endif
            <p class="pb-muted text-xs">قواعد سفارشی اعمال شد.</p>
            @break
        @case('conditions')
            @if ($visible)
                <p class="pb-card text-xs">محتوای مشروط نمایش داده میشود.</p>
            @else
                <div class="pb-card text-xs pb-muted">{!! app(\Elearnac\PageBuilder\Services\RichText::class)->clean((string) $setting('fallback','')) !!}</div>
            @endif
            @break
        @case('sticky')
            <div class="fixed inset-inline-0 z-40 {{ $setting('position','bottom') === 'top' ? 'top-0' : 'bottom-0' }} mx-auto max-w-3xl pb-card" style="inset-inline:{{ (int) $setting('offset',16) }}px">
                <div class="text-xs leading-6">{!! $body !!}</div>
            </div>
            @break
        @case('popup')
            <div x-data="{ open:false }">
                <button type="button" @click="open=true" class="pb-button">{{ e($setting('label','باز کردن پاپآپ')) }}</button>
                <div x-show="open" x-cloak @click.self="open=false" class="fixed inset-0 z-[9000] grid place-items-center bg-slate-950/60 p-4" role="dialog" aria-modal="true">
                    <div class="pb-card w-full max-w-lg p-4 text-xs">{!! $body !!}<button type="button" @click="open=false" class="pb-button mt-3">بستن</button></div>
                </div>
            </div>
            @break
        @case('login-gate')
            @if ($allowed)
                <div class="text-xs leading-6">{!! $body !!}</div>
            @else
                <div class="pb-card text-center text-white" style="background:{{ e($setting('background','#0f172acc')) }}">
                    <p class="text-sm font-bold">{{ e($setting('message','برای مشاهده وارد شوید.')) }}</p>
                    <a href="{{ e($setting('login_url','/login')) }}" class="pb-button mt-3">{{ e($setting('button','ورود به سایت')) }}</a>
                </div>
            @endif
            @break
        @case('global-style')
            <div class="pb-card text-xs pb-muted">سبک سراسری: {{ e(app(\Elearnac\PageBuilder\Services\FontRegistry::class)->options()[$setting('font','site')] ?? 'فونت سایت') }} — {{ e($setting('accent','#18b7a5')) }}</div>
            @break
        @case('template')
            <div class="pb-card text-xs pb-muted">{{ e($setting('fallback','الگویی یافت نشد.')) }}</div>
            @break
        @default
            <div class="pb-card text-xs pb-muted">تنظیم پیشرفته اعمال شد.</div>
    @endswitch
</div>
