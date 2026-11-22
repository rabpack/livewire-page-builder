<div x-data="{ active: 0 }" class="overflow-hidden border border-slate-200 dark:border-slate-700" style="border-radius:{{ (int)$this->setting('radius',10) }}px;">
    <div class="flex gap-1 bg-slate-100 p-1 dark:bg-slate-800">
        @forelse ((array)$this->setting('items',[]) as $index => $item)
            <button type="button" x-on:click="active={{ $index }}" :class="active==={{ $index }} ? 'bg-white text-brand-600 shadow dark:bg-slate-900' : 'text-slate-500'" class="flex-1 rounded-md px-3 py-2 text-xs font-bold">{{ $item['title'] ?? '' }}</button>
        @empty
            <span class="p-2 text-xs text-slate-400">زبانههایی اضافه نشده است.</span>
        @endforelse
    </div>
    @foreach ((array)$this->setting('items',[]) as $index => $item)
        <div x-show="active==={{ $index }}" class="p-4 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $item['body'] ?? '' }}</div>
    @endforeach
</div>
