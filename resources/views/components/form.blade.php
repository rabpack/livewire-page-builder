<div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900" x-data="{}">
    @if ($this->setting('title'))<h3 class="mb-1 text-lg font-extrabold text-slate-800 dark:text-white">{{ $this->setting('title') }}</h3>@endif
    @if ($this->setting('description'))<p class="mb-4 text-xs text-slate-500">{{ $this->setting('description') }}</p>@endif
    @if ($sent)
        <div class="rounded-lg bg-emerald-50 p-4 text-sm font-bold text-emerald-700">{{ $this->setting('success','پیام شما ثبت شد.') }}</div>
    @else
        <form wire:submit.prevent="submit" class="grid grid-cols-12 gap-3">
            @foreach ($fields as $field)
                <div class="col-span-12 md:col-span-6">
                    <label class="mb-1 block text-xs font-bold text-slate-600 dark:text-slate-300">{{ $field['label'] }}</label>
                    @if (($field['type'] ?? 'text') === 'textarea')
                        <textarea rows="4" wire:model="values.{{ $field['name'] }}" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"></textarea>
                    @else
                        <input type="{{ $field['type'] ?? 'text' }}" wire:model="values.{{ $field['name'] }}" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900">
                    @endif
                    @error('values.'.$field['name']) <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                </div>
            @endforeach
            <div class="col-span-12"><button type="submit" class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-bold text-white">{{ $this->setting('button','ارسال') }}</button></div>
        </form>
    @endif
</div>
