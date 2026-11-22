<div class="p-6">
    <h2 class="mb-4 text-lg font-extrabold">پیامهای فرمها</h2>
    <div class="space-y-2">
        @forelse ($submissions as $submission)
            <div class="rounded-lg border border-slate-200 p-3 text-xs dark:border-slate-700">
                <strong>{{ $submission->form_id }}</strong> — {{ $submission->created_at->format('Y/m/d H:i') }}
                <pre class="mt-1 whitespace-pre-wrap">{{ json_encode($submission->payload, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) }}</pre>
            </div>
        @empty
            <p class="text-sm text-slate-400">پیامی ثبت نشده است.</p>
        @endforelse
    </div>
    {{ $submissions->links() }}
</div>
