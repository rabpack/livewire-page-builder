<nav class="{{ app(\Elearnac\PageBuilder\ComponentRegistry::class)->classes('section') }} flex flex-wrap gap-2 text-xs text-slate-500" aria-label="breadcrumb">
    @foreach ($trail as $step)
        @if (!$loop->last) <a href="{{ $step['url'] }}" class="hover:text-brand-500">{{ $step['title'] }}</a><span>/</span> @else <span class="font-bold">{{ $step['title'] }}</span> @endif
    @endforeach
</nav>
