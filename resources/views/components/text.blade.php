<div class="{{ app(\Elearnac\PageBuilder\ComponentRegistry::class)->classes('section') }} leading-8 {{ $this->setting('size') === 'lg' ? 'text-xl' : ($this->setting('size') === 'sm' ? 'text-sm' : 'text-base') }}">
    {!! nl2br(e($this->setting('body',''))) !!}
</div>
