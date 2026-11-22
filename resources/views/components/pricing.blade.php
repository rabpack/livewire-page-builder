<div class="mx-auto max-w-sm overflow-hidden border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900" style="border-radius:16px;box-shadow:{{ $this->setting('highlight',true) ? '0 10px 30px rgba(24,183,165,.18)' : 'none' }};border-color:{{ $this->setting('highlight',true) ? $this->setting('color','#18b7a5') : '#e2e8f0' }};">
    <div class="p-5 text-center" style="background:{{ $this->setting('color','#18b7a5') }}14;">
        <h3 class="text-sm font-extrabold text-slate-700 dark:text-white">{{ $this->setting('plan') }}</h3>
        <p class="mt-2 text-3xl font-black" style="color:{{ $this->setting('color','#18b7a5') }}">{{ $this->setting('price') }} <span class="text-xs font-bold">{{ $this->setting('period') }}</span></p>
    </div>
    <div class="p-5">
        <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-300">
            @foreach ((array)$this->setting('features',[]) as $feature)<li class="flex items-center gap-2"><i class="far fa-check text-brand-500"></i>{{ $feature['title'] ?? '' }}</li>@endforeach
        </ul>
        <a href="{{ $this->setting('link','#') }}" class="mt-5 block rounded-lg py-2.5 text-center text-sm font-extrabold text-white" style="background:{{ $this->setting('color','#18b7a5') }}">{{ $this->setting('button') }}</a>
    </div>
</div>
