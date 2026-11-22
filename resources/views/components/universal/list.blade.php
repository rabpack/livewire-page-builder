@php
    $rows = (array) $setting('items', []);
    $columns = max(1, min(6, (int) $setting('columns', 3)));
    $clean = fn ($value) => app(\Elearnac\PageBuilder\Services\RichText::class)->clean((string) $value);
@endphp
<div class="{{ $classes }}" style="{{ $style }};--pb-columns:{{ $columns }}">
    @switch($variant)
        @case('table')
            <div class="{{ $setting('responsive') ? 'overflow-x-auto' : '' }} pb-card">
                <table class="w-full min-w-[420px] text-start text-xs">
                    @if ($setting('headers', []))
                        <thead class="border-b border-slate-200 dark:border-slate-700"><tr>
                            @foreach ((array) $setting('headers', []) as $header)<th class="p-2 text-start font-extrabold">{{ e($header['title'] ?? '') }}</th>@endforeach
                        </tr></thead>
                    @endif
                    <tbody>
                        @forelse ((array) $setting('rows', []) as $row)
                            <tr class="{{ $setting('striped') ? 'even:bg-slate-50 dark:even:bg-slate-800/40' : '' }} border-b border-slate-100 dark:border-slate-800">
                                @foreach (explode('|', (string) ($row['cells'] ?? '')) as $cell)<td class="p-2">{{ e(trim($cell)) }}</td>@endforeach
                            </tr>
                        @empty
                            <tr><td class="p-3 pb-muted">ردیفی وجود ندارد.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @break
        @case('timeline')
        @case('steps')
            <ol class="{{ $setting('horizontal') ? 'grid gap-3 md:grid-cols-3' : 'space-y-3 border-s border-brand-200 ps-4' }}">
                @forelse ($rows as $item)
                    <li class="relative">
                        @if (! $setting('horizontal'))<span class="absolute -start-[21px] top-1 size-3 rounded-full bg-brand-500 ring-4 ring-white dark:ring-slate-900"></span>@endif
                        <p class="text-sm font-extrabold">{{ e($item['title'] ?? '') }}</p>
                        <div class="pb-muted text-xs">{!! $clean($item['body'] ?? '') !!}</div>
                    </li>
                @empty
                    <li class="pb-muted text-xs">موردی اضافه نشده است.</li>
                @endforelse
            </ol>
            @break
        @case('price-list')
            <div class="space-y-2">
                @forelse ($rows as $item)
                    <div class="pb-card flex items-center justify-between gap-3">
                        <div><p class="text-sm font-bold">{{ e($item['title'] ?? '') }}</p><div class="pb-muted text-xs">{!! $clean($item['body'] ?? '') !!}</div></div>
                        <span class="whitespace-nowrap text-sm font-extrabold text-brand-600 dark:text-brand-300">{{ e($item['price'] ?? '') }}</span>
                    </div>
                @empty
                    <p class="pb-muted text-xs">قیمتی تعریف نشده است.</p>
                @endforelse
            </div>
            @break
        @case('post-meta')
            <div class="flex flex-wrap items-center gap-3 text-xs pb-muted">
                @if ($setting('show_author', true))<span><i class="far fa-user ms-1"></i>مدرس</span>@endif
                @if ($setting('show_date', true))<span><i class="far fa-calendar ms-1"></i>{{ verta()->format('Y/m/d') }}</span>@endif
                @if ($setting('show_comments', true))<span><i class="far fa-comment ms-1"></i>نظرات</span>@endif
            </div>
            @break
        @case('post-categories')
        @case('post-tags')
            <div class="flex flex-wrap gap-2 text-xs">
                @forelse ($items as $item)
                    <a href="{{ e($item['url'] ?? '#') }}" class="pb-chip">{{ e($item['title'] ?? '') }}</a>
                @empty
                    <span class="pb-muted">دستهبندیای وجود ندارد.</span>
                @endforelse
            </div>
            @break
        @case('activity')
            <div class="space-y-2">
                @forelse ($rows as $item)
                    <div class="flex gap-2 rounded-lg border border-slate-200 p-2 text-xs dark:border-slate-700">
                        @if ($item['icon'] ?? '')<i class="{{ e($item['icon']) }} text-brand-500"></i>@endif
                        <div><p class="font-bold">{{ e($item['title'] ?? '') }}</p><p class="pb-muted">{{ e($item['body'] ?? '') }}</p></div>
                    </div>
                @empty
                    <p class="pb-muted text-xs">فعالیتی وجود ندارد.</p>
                @endforelse
            </div>
            @break
        @default
            @if ($variant === 'features')
                <div class="pb-grid">
                    @forelse ($rows as $item)
                        <article class="pb-card">
                            @if ($item['icon'] ?? '')<i class="{{ e($item['icon']) }} text-xl text-brand-500"></i>@endif
                            <h3 class="mt-2 text-sm font-extrabold">{{ e($item['title'] ?? '') }}</h3>
                            <div class="pb-muted mt-1 text-xs">{!! $clean($item['body'] ?? '') !!}</div>
                        </article>
                    @empty
                        <p class="pb-muted text-xs">ویژگیای وجود ندارد.</p>
                    @endforelse
                </div>
            @else
                <ul class="space-y-2">
                    @forelse ($rows as $item)
                        <li class="flex gap-2 text-sm">
                            @if ($item['icon'] ?? $setting('icon',''))<i class="{{ e($item['icon'] ?? $setting('icon')) }} mt-1 text-brand-500"></i>@endif
                            <div><span class="font-bold">{{ e($item['title'] ?? '') }}</span><div class="pb-muted text-xs">{!! $clean($item['body'] ?? '') !!}</div></div>
                        </li>
                    @empty
                        <li class="pb-muted text-xs">موردی اضافه نشده است.</li>
                    @endforelse
                </ul>
            @endif
    @endswitch
</div>
