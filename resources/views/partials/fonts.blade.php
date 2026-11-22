@php($fonts = app(\Elearnac\PageBuilder\Services\FontRegistry::class))
@foreach ($fonts->stylesheets() as $stylesheet)
    @php($href = str_starts_with($stylesheet, 'http') || str_starts_with($stylesheet, '//') || str_starts_with($stylesheet, '/') ? $stylesheet : asset($stylesheet))
    <link rel="stylesheet" href="{{ e($href) }}">
@endforeach
@if (trim($fonts->css()) !== '')
    <style data-page-builder-fonts>{!! $fonts->css() !!}</style>
@endif
