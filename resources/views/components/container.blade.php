<div class="{{ $this->setting('classes','') }}" style="background:{{ $this->setting('background','#ffffff') }};padding:{{ (int)$this->setting('padding',24) }}px;border-radius:{{ (int)$this->setting('radius',14) }}px;">
    @include('page-builder::components.partials.children', ['children' => $children, 'editable' => $editable, 'path' => $path])
</div>
