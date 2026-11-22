@php
    $networks = [
        'instagram' => 'fab fa-instagram', 'telegram' => 'fab fa-telegram-plane', 'whatsapp' => 'fab fa-whatsapp',
        'twitter' => 'fab fa-twitter', 'x' => 'fab fa-x-twitter', 'facebook' => 'fab fa-facebook-f',
        'youtube' => 'fab fa-youtube', 'linkedin' => 'fab fa-linkedin-in', 'tiktok' => 'fab fa-tiktok',
    ];
@endphp
<div class="{{ $classes }}" style="{{ $style }}">
    @if ($variant === 'share')
        <div class="flex flex-wrap items-center gap-2">
            @if ($setting('label',''))<span class="text-xs font-bold pb-muted">{{ e($setting('label')) }}</span>@endif
            @foreach ((array) $setting('networks', []) as $network)
                @php($key = $network['network'] ?? 'telegram')
                <a href="#" data-share="{{ e($key) }}" class="pb-chip"><i class="{{ e($networks[$key] ?? 'far fa-share-alt') }}"></i></a>
            @endforeach
        </div>
    @elseif ($variant === 'embed')
        <div class="pb-card text-center text-xs pb-muted">
            <i class="fas fa-link text-brand-500"></i>
            <p class="mt-2">{{ e($setting('url') ?: ($setting('username') ? '@'.$setting('username') : 'لینک شبکه را تنظیم کنید')) }}</p>
        </div>
    @else
        <div class="flex flex-wrap gap-2">
            @forelse ((array) $setting('items', []) as $item)
                @php($key = $item['network'] ?? 'instagram')
                <a href="{{ e($item['url'] ?? '#') }}" class="grid size-9 place-items-center rounded-{{ $setting('shape','circle') === 'circle' ? 'full' : 'lg' }} bg-slate-100 text-slate-700 transition-colors hover:bg-brand-500 hover:text-white dark:bg-slate-800 dark:text-slate-200" aria-label="{{ e($key) }}">
                    <i class="{{ e($networks[$key] ?? 'far fa-link') }}"></i>
                </a>
            @empty
                <p class="pb-muted text-xs">شبکهای وجود ندارد.</p>
            @endforelse
        </div>
    @endif
</div>
