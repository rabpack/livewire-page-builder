@php
    $deviceLabels = [
        'desktop' => 'دسکتاپ',
        'wide' => 'عریض',
        'tablet' => 'تبلت',
        'mobile' => 'موبایل',
    ];
    $currentDevice = $designDevice ?? 'desktop';
@endphp
<div class="mb-3 rounded-lg border border-slate-200 bg-slate-50 p-2 dark:border-slate-700 dark:bg-slate-800/60">
    <div class="mb-1.5 flex items-center gap-1.5 text-[10px] font-bold text-slate-600 dark:text-slate-300">
        <i class="far fa-devices text-[10px] text-brand-500"></i>طراحی مخصوص هر دستگاه
    </div>
    <div class="flex gap-1">
        @foreach ($deviceLabels as $key => $label)
            <button type="button" wire:click="setDesignDevice('{{ $key }}')"
                    @class([
                        'flex-1 rounded-md px-1 py-1.5 text-[10px] font-bold transition-colors',
                        'bg-brand-500 text-white' => $currentDevice === $key,
                        'bg-white text-slate-500 hover:text-brand-600 dark:bg-slate-900' => $currentDevice !== $key,
                    ])>{{ $label }}</button>
        @endforeach
    </div>
    @if ($currentDevice !== 'desktop')
        <p class="mt-1.5 text-[10px] leading-4 text-slate-400">تغییرات فعلی فقط برای «{{ $deviceLabels[$currentDevice] }}» ذخیره میشود.</p>
    @endif
</div>
