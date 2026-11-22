@extends(config('page-builder.front_layout', 'general::layouts.layout'))

@section('content')
    @include('page-builder::partials.fonts')
    @include('page-builder::partials.style')
    <div class="pb-page min-h-screen py-8" style="{{ app(\Elearnac\PageBuilder\Services\StyleCompiler::class)->pageVariables($settings) }}">
        <div class="{{ app(\Elearnac\PageBuilder\ComponentRegistry::class)->classes('container') }}">
            @if ($settings['header'] ?? true)
                <div class="mb-6 border-b border-slate-200 pb-4 text-lg font-extrabold">{{ $title }}</div>
            @endif

            @foreach ($blocks as $block)
                @livewire('page-builder.component.'.$block['type'], app(\Elearnac\PageBuilder\ComponentRegistry::class)->mountProps($block['type'], [
                    'settings' => $block['settings'],
                    'children' => $block['children'] ?? [],
                    'editable' => false,
                    'path' => (string) $loop->index,
                ]), key('preview-'.$loop->index.'-'.$block['type']))
            @endforeach

            @if ($settings['footer'] ?? true)
                <div class="mt-8 border-t border-slate-200 pt-4 text-center text-xs text-slate-500">پاورقی صفحه</div>
            @endif
        </div>
    </div>
@endsection
