<div class="{{ app(\Elearnac\PageBuilder\ComponentRegistry::class)->classes('section') }}">
    @if ($this->setting('layout','grid') === 'slider')
        <div class="flex gap-4 overflow-x-auto pb-3">
            @foreach ($items as $item)
                <div class="{{ app(\Elearnac\PageBuilder\ComponentRegistry::class)->classes('card') }} min-w-64 p-4"><h4 class="font-bold">{{ $item['title'] }}</h4><p class="text-sm">{{ $item['body'] }}</p></div>
            @endforeach
        </div>
    @elseif ($this->setting('layout') === 'stacked')
        <div class="space-y-3">
            @foreach ($items as $item)
                <div class="{{ app(\Elearnac\PageBuilder\ComponentRegistry::class)->classes('card') }} p-4"><h4 class="font-bold">{{ $item['title'] }}</h4><p class="text-sm">{{ $item['body'] }}</p></div>
            @endforeach
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-{{ max(1,(int)$this->setting('columns',3)) }}">
            @foreach ($items as $item)
                <div class="{{ app(\Elearnac\PageBuilder\ComponentRegistry::class)->classes('card') }} p-4"><h4 class="font-bold">{{ $item['title'] }}</h4><p class="text-sm">{{ $item['body'] }}</p></div>
            @endforeach
        </div>
    @endif
</div>
