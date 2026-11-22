<div class="{{ app(\Elearnac\PageBuilder\ComponentRegistry::class)->classes('section') }}">
    @auth
        <div class="rounded-xl border border-slate-200 p-4">محتوای شما</div>
    @else
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 p-10 text-center dark:border-slate-700" style="filter: blur({{ (int)$this->setting('blur',10) }}px); opacity:.45; pointer-events:none;">
            محتوای محافظت شده
        </div>
        <div class="-mt-16 relative text-center">
            <p class="mb-3 text-sm font-bold">{{ $this->setting('message','') }}</p>
            <a href="{{ route('login') }}" class="inline-block rounded-lg bg-brand-500 px-5 py-2 text-sm font-bold text-white">ورود به سایت</a>
        </div>
    @endauth
</div>
