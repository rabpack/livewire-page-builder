<div x-data="{ open: {{ $this->setting('open_first',true) ? 0 : -1 }} }" class="space-y-2">
    @forelse ((array)$this->setting('items',[]) as $index => $item)
        <div class="overflow-hidden border border-slate-200 dark:border-slate-700" style="border-radius:{{ (int)$this->setting('radius',10) }}px;">
            <button type="button" x-on:click="open = open === {{ $index }} ? -1 : {{ $index }}" class="flex w-full items-center justify-between p-3 text-start text-sm font-extrabold text-slate-700 dark:text-slate-200">
                <span>{{ $item['title'] ?? '' }}</span><i class="far fa-chevron-up text-xs" :class="open === {{ $index }} ? '' : 'rotate-180'"></i>
            </button>
            <div x-show="open === {{ $index }}" x-collapse class="border-t border-slate-200 p-3 text-xs leading-6 text-slate-500 dark:border-slate-700">{{ $item['body'] ?? '' }}</div>
        </div>
    @empty
        <p class="text-xs text-slate-400">موردی اضافه نشده است.</p>
    @endforelse
</div>
