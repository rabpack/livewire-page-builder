<div class="text-center" x-data="{}">
    <div class="font-black" style="color:{{ $this->setting('color','#0f3d6e') }};font-size:{{ (int)$this->setting('size',36) }}px;">{{ $this->setting('prefix','') }}{{ number_format((int)$this->setting('number',0)) }}{{ $this->setting('suffix','') }}</div>
    <div class="mt-1 text-xs font-bold text-slate-500 dark:text-slate-400">{{ $this->setting('label') }}</div>
</div>
