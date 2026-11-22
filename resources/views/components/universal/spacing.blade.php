<div class="{{ $classes }} @if ($variant !== 'divider') {{ $setting('hide_mobile') ? 'hidden sm:block' : '' }} @endif"
     style="{{ $variant === 'divider' ? $style : $style.';height:'.max(0, (int) $setting('height', 40)).'px' }}"
     @if ($variant !== 'divider') aria-hidden="true" @endif>
    @if ($variant === 'divider')
        <div style="width:{{ (int) $setting('width', 100) }}%;margin-block:{{ (int) $setting('margin', 16) }}px;margin-inline:{{ $setting('align', 'right') === 'center' ? 'auto' : '0' }};border-top:{{ (int) $setting('thickness', 1) }}px {{ $setting('style', 'solid') }} var(--pb-border,#cbd5e1)"></div>
    @endif
</div>
