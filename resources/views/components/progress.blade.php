@php($value=max(0,min(100,(int)$this->setting('value',75))))
<div>
    @if($this->setting('label'))<div class="mb-1 flex justify-between text-xs font-bold text-slate-600 dark:text-slate-300"><span>{{ $this->setting('label') }}</span><span>{{ $value }}%</span></div>@endif
    <div class="overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700" style="height:{{ (int)$this->setting('height',10) }}px;">
        <div class="h-full rounded-full transition-all duration-700" style="width:{{ $value }}%;background:{{ $this->setting('color','#18b7a5') }};"></div>
    </div>
</div>
