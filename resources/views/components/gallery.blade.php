<div class="{{ app(\Elearnac\PageBuilder\ComponentRegistry::class)->classes('section') }}" x-data="{ open:'' }">
    <div class="grid gap-3 md:grid-cols-{{ max(1,(int)$this->setting('columns',3)) }}">
        @foreach ($images as $image)
            <button type="button" @click="open=@js($image)" class="overflow-hidden" style="border-radius:{{ (int)$this->setting('radius',14) }}px">
                <img src="{{ $image }}" alt="" class="h-48 w-full object-cover transition-transform hover:scale-105">
            </button>
        @endforeach
    </div>
    <div x-show="open" x-cloak @click="open=''" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-6">
        <img :src="open" alt="" class="max-h-full max-w-full rounded-xl">
    </div>
</div>
