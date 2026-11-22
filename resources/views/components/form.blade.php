<div class="{{ app(\Elearnac\PageBuilder\ComponentRegistry::class)->classes('card') }} {{ app(\Elearnac\PageBuilder\ComponentRegistry::class)->classes('section') }} p-6">
    <h3 class="mb-4 text-lg font-extrabold">{{ $this->setting('title','فرم') }}</h3>
    @if ($sent)
        <p class="text-sm font-bold text-emerald-600">پیام شما با موفقیت ثبت شد.</p>
    @else
        <form wire:submit="submit" class="space-y-3">
            @foreach ($fields as $field)
                <div>
                    <label class="mb-1 block text-xs font-bold">{{ $field['label'] }}</label>
                    @if ($field['type'] === 'textarea')
                        <textarea wire:model="values.{{ $field['name'] }}" rows="4" class="w-full rounded-lg border border-slate-200 p-2 text-sm dark:border-slate-700 dark:bg-slate-900"></textarea>
                    @else
                        <input type="{{ $field['type'] }}" wire:model="values.{{ $field['name'] }}" class="w-full rounded-lg border border-slate-200 p-2 text-sm dark:border-slate-700 dark:bg-slate-900">
                    @endif
                    @error('values.'.$field['name']) <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
            @endforeach
            <button class="rounded-lg bg-brand-500 px-5 py-2 text-sm font-bold text-white">{{ $this->setting('button','ارسال') }}</button>
        </form>
    @endif
</div>
