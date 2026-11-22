@php
    $fields = (array) $setting('fields', []);
    $inputTypes = ['text' => 'text', 'email' => 'email', 'number' => 'number', 'tel' => 'tel', 'textarea' => 'textarea', 'select' => 'select'];
@endphp
<div class="{{ $classes }}" style="{{ $style }}">
    @switch($variant)
        @case('newsletter')
            <div class="pb-card">
                @if ($setting('title',''))<h3 class="text-sm font-extrabold">{{ e($setting('title')) }}</h3>@endif
                @if ($setting('description',''))<p class="pb-muted mt-1 text-xs">{{ e($setting('description')) }}</p>@endif
                <form class="mt-3 flex flex-col gap-2 sm:flex-row" onsubmit="return false">
                    <input type="email" class="pb-input" placeholder="{{ e($setting('placeholder','آدرس ایمیل')) }}" aria-label="ایمیل">
                    <button class="pb-button">{{ e($setting('button','عضویت')) }}</button>
                </form>
            </div>
            @break
        @case('search')
            <form class="pb-card flex flex-col gap-2 sm:flex-row" action="{{ e($setting('action','/search')) }}" method="get">
                <input type="search" name="q" class="pb-input" placeholder="{{ e($setting('placeholder','جستجو')) }}" aria-label="جستجو">
                <button class="pb-button">{{ e($setting('button','جستجو')) }}</button>
            </form>
            @break
        @case('login')
        @case('register')
        @case('lost-password')
        @case('reset-password')
        @case('profile')
        @case('ticket')
        @case('booking')
        @case('survey')
            <div class="pb-card">
                @if ($setting('title',''))<h3 class="text-sm font-extrabold">{{ e($setting('title')) }}</h3>@endif
                @if ($setting('description',''))<p class="pb-muted mt-1 text-xs">{{ e($setting('description')) }}</p>@endif
                @if ($variant === 'survey' && $setting('show_progress'))<div class="mt-3 h-1.5 rounded-full bg-slate-200 dark:bg-slate-700"><div class="h-full w-1/3 rounded-full bg-brand-500"></div></div>@endif
                @if ($variant === 'profile' && $setting('show_avatar'))<div class="mx-auto my-3 size-16 rounded-full bg-slate-200 dark:bg-slate-700"></div>@endif
                @csrf
                <div class="mt-3 space-y-2">
                    @if (in_array($variant, ['login','register','lost-password','reset-password','profile'], true))
                        @if ($variant !== 'login')<input class="pb-input" type="text" placeholder="نام" aria-label="نام">@endif
                        @if (! in_array($variant, ['lost-password','profile'], true))<input class="pb-input" type="email" placeholder="ایمیل" aria-label="ایمیل">@endif
                        @if (! in_array($variant, ['lost-password'], true))<input class="pb-input" type="password" placeholder="رمز عبور" aria-label="رمز عبور">@endif
                    @else
                        @foreach ($fields as $field)
                            <div>
                                <label class="mb-1 block text-[11px] font-bold">{{ e($field['label'] ?? $field['name'] ?? '') }}</label>
                                @php($type = $inputTypes[$field['type'] ?? 'text'] ?? 'text')
                                @if ($type === 'textarea')
                                    <textarea class="pb-input" rows="3" name="{{ e($field['name'] ?? '') }}" @if($field['required'] ?? false) required @endif></textarea>
                                @elseif ($type === 'select')
                                    <select class="pb-input" name="{{ e($field['name'] ?? '') }}">@foreach (explode('|', (string) ($field['options'] ?? '')) as $option)<option>{{ e(trim($option)) }}</option>@endforeach</select>
                                @else
                                    <input class="pb-input" type="{{ $type }}" name="{{ e($field['name'] ?? '') }}" @if($field['required'] ?? false) required @endif>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
                @if ($variant === 'booking')<label class="mt-2 block text-[11px] font-bold">{{ e($setting('date_label','تاریخ')) }}<input type="text" class="pb-input mt-1" data-jalali="1"></label>@endif
                <button type="button" class="pb-button mt-3 w-full">{{ e($setting('button','ارسال')) }}</button>
                <p class="pb-muted mt-2 text-[10px]">{{ e($setting('success','')) }}</p>
            </div>
            @break
        @case('hook')
            <div class="pb-card text-xs pb-muted">{{ e($setting('body','فرمی برای نمایش وجود ندارد.')) }} <span dir="ltr">[{{ e($setting('hook','')) }}]</span></div>
            @break
        @default
            <form class="pb-card" onsubmit="return false">
                @csrf
                @if ($setting('title',''))<h3 class="mb-3 text-sm font-extrabold">{{ e($setting('title')) }}</h3>@endif
                @if ($setting('description',''))<p class="pb-muted mb-3 text-xs">{{ e($setting('description')) }}</p>@endif
                <div class="space-y-2">
                    @forelse ($fields as $field)
                        <div>
                            <label class="mb-1 block text-[11px] font-bold">{{ e($field['label'] ?? $field['name'] ?? '') }}</label>
                            @php($type = $inputTypes[$field['type'] ?? 'text'] ?? 'text')
                            @if ($type === 'textarea')
                                <textarea class="pb-input" rows="3" @if($field['required'] ?? false) required @endif></textarea>
                            @elseif ($type === 'select')
                                <select class="pb-input">@foreach (explode('|', (string) ($field['options'] ?? '')) as $option)<option>{{ e(trim($option)) }}</option>@endforeach</select>
                            @else
                                <input class="pb-input" type="{{ $type }}" @if($field['required'] ?? false) required @endif>
                            @endif
                        </div>
                    @empty
                        <p class="pb-muted text-xs">فیلدی تعریف نشده است.</p>
                    @endforelse
                </div>
                <button class="pb-button mt-3 w-full">{{ e($setting('button','ارسال')) }}</button>
            </form>
    @endswitch
</div>
