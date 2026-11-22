@php($colors=['info'=>'#0ea5e9','success'=>'#10b981','warning'=>'#f59e0b','danger'=>'#e11d48'])
<div x-data="{ show:true }" x-show="show" @if($this->setting('dismissible',true)) x-transition @endif
     class="flex items-start gap-3 p-4 text-sm" style="border-inline-start:4px solid {{ $colors[$this->setting('type','info')] ?? '#0ea5e9' }};background:{{ $colors[$this->setting('type','info')] ?? '#0ea5e9' }}14;border-radius:{{ (int)$this->setting('radius',10) }}px;color:{{ $colors[$this->setting('type','info')] ?? '#0ea5e9' }};">
    <div class="flex-1"><strong class="block font-extrabold">{{ $this->setting('title') }}</strong><span class="mt-1 block opacity-90">{{ $this->setting('body') }}</span></div>
    @if($this->setting('dismissible',true))<button type="button" x-on:click="show=false" class="text-lg leading-none">×</button>@endif
</div>
