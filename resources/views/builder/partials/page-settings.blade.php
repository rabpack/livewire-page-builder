<div class="space-y-3">
    <section class="rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-800/60">
        <h3 class="mb-2 flex items-center gap-1.5 text-xs font-extrabold text-slate-700 dark:text-slate-200">
            <i class="far fa-layout text-[10px] text-brand-500"></i>الگوهای آماده
        </h3>
        <div class="space-y-2">
            @foreach (config('page-builder.templates', []) as $key => $template)
                <button type="button" wire:click="applyTemplate('{{ $key }}')"
                        class="flex w-full items-center gap-2 rounded-lg border border-slate-200 p-2 text-start transition-colors hover:border-brand-400 hover:bg-brand-50 dark:border-slate-700 dark:hover:bg-brand-500/10">
                    <i class="far fa-th-large text-xs text-brand-500"></i>
                    <span class="min-w-0">
                        <span class="block text-[11px] font-extrabold text-slate-700 dark:text-slate-200">{{ $template['label'] }}</span>
                        <span class="mt-0.5 block text-[10px] text-slate-400">{{ $template['description'] }}</span>
                    </span>
                </button>
            @endforeach
        </div>
        @error('template')<p class="mt-2 text-[10px] text-rose-600">{{ $message }}</p>@enderror
    </section>

    <section class="rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-800/60">
        <h3 class="mb-2 flex items-center gap-1.5 text-xs font-extrabold text-slate-700 dark:text-slate-200">
            <i class="far fa-copy text-[10px] text-brand-500"></i>کپی و چسباندن المان
        </h3>
        <div class="flex gap-2">
            <button type="button" wire:click="copyBlock" class="flex-1 rounded-lg border border-slate-200 py-2 text-[11px] font-bold hover:border-brand-400 dark:border-slate-700">کپی المان</button>
            <button type="button" wire:click="pasteBlock" class="flex-1 rounded-lg border border-slate-200 py-2 text-[11px] font-bold hover:border-brand-400 dark:border-slate-700">چسباندن</button>
        </div>
        @error('clipboard')<p class="mt-2 text-[10px] text-rose-600">{{ $message }}</p>@enderror
    </section>

    <section class="rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-800/60">
        <h3 class="mb-2 flex items-center gap-1.5 text-xs font-extrabold text-slate-700 dark:text-slate-200">
            <i class="far fa-file-export text-[10px] text-brand-500"></i>انتقال صفحه
        </h3>
        <div class="flex gap-2">
            <button type="button" wire:click="prepareExport" class="flex-1 rounded-lg border border-slate-200 py-2 text-[11px] font-bold hover:border-brand-400 dark:border-slate-700">صادر کردن JSON</button>
            <button type="button" wire:click="importDocument" class="flex-1 rounded-lg border border-slate-200 py-2 text-[11px] font-bold hover:border-brand-400 dark:border-slate-700">وارد کردن JSON</button>
        </div>
        <p class="mt-1.5 text-[10px] text-slate-400">ساختار صفحه را میتوانید ذخیره یا در صفحه دیگری بازگردانی کنید.</p>
        <textarea wire:model="exportText" rows="3" readonly class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 p-2 text-[10px] dark:border-slate-700 dark:bg-slate-950" dir="ltr" placeholder="پس از صادر کردن، محتوای صفحه اینجا نمایش داده میشود."></textarea>
        <textarea wire:model="importText" rows="3" class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 p-2 text-[10px] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" dir="ltr" placeholder="محتوای JSON صفحه را اینجا بچسبانید."></textarea>
        @error('importText')<p class="mt-2 text-[10px] text-rose-600">{{ $message }}</p>@enderror
    </section>

    <section class="rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-800/60">
        <h3 class="mb-3 flex items-center gap-1.5 text-xs font-extrabold text-slate-700 dark:text-slate-200">
            <i class="far fa-palette text-[10px] text-brand-500"></i>طرح صفحه
        </h3>
        <div class="space-y-2.5">
            <div>
                <label class="mb-1 flex items-center gap-1.5 text-[11px] font-bold text-slate-600 dark:text-slate-300"><i class="far fa-font text-[10px] text-brand-500"></i>فونت صفحه</label>
                <select wire:model.live="pageSettings.font" class="h-9 w-full rounded-lg border border-slate-200 bg-slate-50 px-2 text-xs dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                    @foreach ($fonts as $fontKey => $fontLabel)<option value="{{ $fontKey }}">{{ $fontLabel }}</option>@endforeach
                </select>
                <p class="mt-1 text-[10px] text-slate-400">فونت پیشفرض تمام المانهایی که فونت جداگانی ندارند.</p>
            </div>

            @foreach ([
                ['background', 'پسزمینه صفحه', 'far fa-fill-drip'],
                ['background_dark', 'پسزمینه حالت تاریک', 'far fa-moon'],
                ['text', 'رنگ متن', 'far fa-text-height'],
                ['text_dark', 'رنگ متن حالت تاریک', 'far fa-font'],
                ['accent', 'رنگ اصلی', 'far fa-highlighter'],
            ] as $colorSetting)
                <div>
                    <label class="mb-1 flex items-center gap-1.5 text-[11px] font-bold text-slate-600 dark:text-slate-300">
                        <i class="{{ $colorSetting[2] }} text-[10px] text-brand-500"></i>{{ $colorSetting[1] }}
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="color" wire:model.live="pageSettings.{{ $colorSetting[0] }}" class="size-9 cursor-pointer rounded-md border border-slate-200 bg-transparent p-0.5 dark:border-slate-700">
                        <input type="text" dir="ltr" wire:model.live="pageSettings.{{ $colorSetting[0] }}" class="h-9 flex-1 rounded-lg border border-slate-200 bg-slate-50 px-2 text-xs dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                    </div>
                </div>
            @endforeach

            @foreach ([
                ['container_width', 'عرض محتوا (پیکسل)', 'far fa-arrows-alt-h', 320, 2200],
                ['radius', 'گردی پیشفرض (پیکسل)', 'far fa-square', 0, 48],
            ] as $numberSetting)
                <div>
                    <label class="mb-1 flex items-center gap-1.5 text-[11px] font-bold text-slate-600 dark:text-slate-300">
                        <i class="{{ $numberSetting[2] }} text-[10px] text-brand-500"></i>{{ $numberSetting[1] }}
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="range" min="{{ $numberSetting[3] }}" max="{{ $numberSetting[4] }}" wire:model.live="pageSettings.{{ $numberSetting[0] }}" class="h-1.5 flex-1 accent-brand-500">
                        <input type="number" min="{{ $numberSetting[3] }}" max="{{ $numberSetting[4] }}" wire:model.live="pageSettings.{{ $numberSetting[0] }}" class="h-8 w-20 rounded-lg border border-slate-200 bg-slate-50 px-1.5 text-center text-xs dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                    </div>
                </div>
            @endforeach

            @foreach (['header' => ['نمایش هدر سایت', 'far fa-window-maximize'], 'footer' => ['نمایش فوتر سایت', 'far fa-window-minimize']] as $key => $toggle)
                <label class="flex cursor-pointer items-center justify-between rounded-lg border border-slate-200 px-2 py-1.5 text-[11px] font-bold text-slate-600 dark:border-slate-700 dark:text-slate-300">
                    <span class="flex items-center gap-1.5"><i class="{{ $toggle[1] }} text-[10px] text-brand-500"></i>{{ $toggle[0] }}</span>
                    <input type="checkbox" class="size-4 accent-brand-500" wire:model.live="pageSettings.{{ $key }}">
                </label>
            @endforeach
        </div>
    </section>

    <a href="{{ route('page-builder.submissions') }}" class="flex items-center justify-center gap-1.5 rounded-lg border border-brand-200 bg-brand-50 px-3 py-2 text-[11px] font-bold text-brand-700 dark:border-brand-500/30 dark:bg-brand-500/10 dark:text-brand-300">
        <i class="far fa-inbox text-[10px]"></i>مشاهده پیامهای فرمها
    </a>
    <button type="button" wire:click="clearPage" class="flex w-full items-center justify-center gap-1.5 rounded-lg border border-rose-200 px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 dark:border-rose-500/30">
        <i class="far fa-trash-alt text-[10px]"></i>پاک کردن پیشنویس
    </button>
</div>
