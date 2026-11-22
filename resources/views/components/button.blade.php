<div class="flex justify-{{ $this->setting('align','right') }}" x-data="{}">
    <a href="{{ $this->setting('link','#') }}" target="{{ $this->setting('target','_self') }}"
       class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-extrabold transition-opacity hover:opacity-90"
       style="background:{{ $this->setting('style','solid')==='solid' ? $this->setting('color','#18b7a5') : 'transparent' }};color:{{ $this->setting('style','solid')==='solid' ? '#fff' : $this->setting('color','#18b7a5') }};border:{{ $this->setting('style')==='outline' ? '2px solid '.$this->setting('color','#18b7a5') : '0' }};border-radius:{{ (int)$this->setting('radius',8) }}px;font-size:{{ $this->setting('size','md')==='lg' ? '16px' : ($this->setting('size')==='sm' ? '12px' : '14px') }};">
        {{ $this->setting('label','ادامه مطلب') }}
    </a>
</div>
