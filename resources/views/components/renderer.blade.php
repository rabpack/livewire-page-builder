@include('page-builder::partials.fonts')
@include('page-builder::partials.style')
@include('page-builder::partials.page-style')
@include('page-builder::partials.block-style')
<div class="pb-page min-h-screen" style="{{ app(\Elearnac\PageBuilder\Services\StyleCompiler::class)->pageVariables($settings) }}">
    <h1 class="sr-only">{{ $page->title }}</h1>
    @if ($settings['header'] ?? true)
        <header class="border-b border-slate-200 bg-white/80 px-6 py-4 dark:border-slate-800 dark:bg-slate-900/80">
            <div class="{{ app(\Elearnac\PageBuilder\ComponentRegistry::class)->classes('container') }} font-extrabold">{{ $page->title }}</div>
        </header>
    @endif
    <main class="{{ app(\Elearnac\PageBuilder\ComponentRegistry::class)->classes('container') }} py-8">
        @foreach ($blocks as $block)
            <div data-pb-path="{{ (string) $loop->index }}">
                @livewire('page-builder.component.'.$block['type'], $registry->mountProps($block['type'], [
                    'settings' => $block['settings'],
                    'children' => $block['children'] ?? [],
                    'editable' => false,
                    'path' => (string) $loop->index,
                ]), key('render-'.$loop->index.'-'.$block['type']))
            </div>
        @endforeach
    </main>
    @if ($settings['footer'] ?? true)
        <footer class="border-t border-slate-200 px-6 py-6 text-center text-xs text-slate-500 dark:border-slate-800">© {{ now()->year }}</footer>
    @endif
</div>
