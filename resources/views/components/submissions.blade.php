<div class="space-y-4 p-4" dir="rtl">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800 dark:text-white">پیامهای فرمها</h1>
            <p class="mt-1 text-xs text-slate-500">پیامهای ارسالشده از فرمهای صفحهساز را بررسید و فیلتر کنید.</p>
        </div>
        <select wire:model.live="formId" class="rounded-lg border border-slate-200 px-3 py-2 text-xs dark:border-slate-700 dark:bg-slate-900">
            <option value="">همه فرمها</option>
            @foreach ($forms as $form)
                <option value="{{ $form }}">{{ $form }}</option>
            @endforeach
        </select>
    </div>

    @forelse ($submissions as $submission)
        <div class="rounded-xl border border-slate-200 bg-white p-4 text-xs dark:border-slate-700 dark:bg-slate-900">
            <div class="flex items-center justify-between text-slate-500">
                <strong class="text-slate-700 dark:text-slate-200">{{ $submission->form_id }}</strong>
                <span>{{ verta($submission->created_at)->format('Y/m/d H:i') }}</span>
            </div>
            <dl class="mt-3 grid gap-2 sm:grid-cols-2">
                @foreach ((array) $submission->payload as $key => $value)
                    <div class="rounded-lg bg-slate-50 p-2 dark:bg-slate-800">
                        <dt class="font-bold text-slate-500">{{ $key }}</dt>
                        <dd class="mt-1 whitespace-pre-wrap text-slate-700 dark:text-slate-200">{{ is_scalar($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE) }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    @empty
        <div class="rounded-xl border border-dashed border-slate-300 p-12 text-center text-sm text-slate-400">پیامی ثبت نشده است.</div>
    @endforelse

    {{ $submissions->links() }}
</div>
