<div class="relative">
    @if (count($items))
        <div class="grid" style="grid-template-columns:repeat({{ max(1, (int) $this->setting('columns', 4)) }},minmax(0,1fr));gap:{{ (int) $this->setting('gap', 12) }}px;">
            @foreach ($items as $item)
                <figure class="overflow-hidden bg-slate-100 dark:bg-slate-800" style="border-radius:{{ (int) $this->setting('radius', 14) }}px;">
                    <img src="{{ $item['url'] }}" alt="{{ $item['title'] ?: 'تصویر گالری' }}"
                         class="h-40 w-full object-{{ $this->setting('image_fit', 'cover') }} transition-transform duration-500 hover:scale-105"
                         @if ($this->setting('lightbox', true))
                             data-pb-lightbox="{{ $item['url'] }}"
                             x-on:click="window.dispatchEvent(new CustomEvent('pb-lightbox',{detail:{url:'{{ $item['url'] }}',title:'{{ $item['title'] }}'}}))"
                        @endif>
                    @if ($this->setting('show_title', true) && $item['title'])
                        <figcaption class="truncate p-2 text-xs font-bold text-slate-700 dark:text-slate-200">{{ $item['title'] }}</figcaption>
                    @endif
                </figure>
            @endforeach
        </div>
        @if ($this->setting('lightbox', true))
            <div x-data="{ open:false, url:'', title:'' }"
                 x-on:pb-lightbox.window="open=true; url=$event.detail.url; title=$event.detail.title"
                 x-show="open" x-cloak
                 class="fixed inset-0 z-[9999] grid place-items-center bg-black/80 p-6"
                 x-on:click.self="open=false">
                <div class="max-w-5xl text-center">
                    <img :src="url" :alt="title" class="mx-auto max-h-[80vh] rounded-lg">
                    <p class="mt-3 text-sm text-white" x-text="title"></p>
                    <button type="button" x-on:click="open=false" class="mt-3 rounded-lg bg-white/20 px-4 py-2 text-sm text-white">بستن</button>
                </div>
            </div>
        @endif
    @else
        <div class="grid min-h-40 place-items-center rounded-xl border border-dashed border-slate-300 text-xs text-slate-400">
            تصویری یافت نشد. منبع گالری را بررسی کنید.
        </div>
    @endif
</div>
