@php($url = app(\Elearnac\PageBuilder\Services\MediaResolver::class)->url(is_array($this->setting('image',[])) ? ($this->setting('image')[0] ?? null) : $this->setting('image')))
<div x-data="{}">
    @if ($url)
        <figure class="overflow-hidden" style="border-radius:{{ (int)$this->setting('radius',12) }}px;">
            @if ($this->setting('link'))
                <a href="{{ $this->setting('link') }}"><img src="{{ $url }}" alt="{{ $this->setting('alt','') }}" class="w-full object-{{ $this->setting('fit','cover') }}" style="height:{{ (int)$this->setting('height',280) }}px" @if($this->setting('lightbox',true)) x-on:click.prevent="window.dispatchEvent(new CustomEvent('pb-lightbox',{detail:{url:'{{ $url }}',title:'{{ $this->setting('alt','') }}'}}))" @endif></a>
            @else
                <img src="{{ $url }}" alt="{{ $this->setting('alt','') }}" class="w-full object-{{ $this->setting('fit','cover') }}" style="height:{{ (int)$this->setting('height',280) }}px" @if($this->setting('lightbox',true)) x-on:click="window.dispatchEvent(new CustomEvent('pb-lightbox',{detail:{url:'{{ $url }}',title:'{{ $this->setting('alt','') }}'}}))" @endif>
            @endif
            @if ($this->setting('caption'))<figcaption class="p-2 text-xs text-slate-500">{{ $this->setting('caption') }}</figcaption>@endif
        </figure>
    @else
        <div class="grid min-h-32 place-items-center rounded-xl border border-dashed border-slate-300 text-xs text-slate-400">برای نمایش تصویر، از مدارک سایت انتخاب کنید.</div>
    @endif
</div>
