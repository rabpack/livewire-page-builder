<div class="{{ $this->setting('classes','') }}" style="background:{{ $this->setting('background','#ffffff') }};display:grid;grid-template-columns:repeat({{ max(1,(int)$this->setting('columns',2)) }},minmax(0,1fr));gap:{{ (int)$this->setting('gap',16) }}px;">
    @include('page-builder::components.partials.children', ['children' => $children, 'editable' => $editable, 'path' => $path])
</div>
