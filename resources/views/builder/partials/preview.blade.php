@php
    $deviceWidths = ['wide' => '100%', 'mobile' => '390px', 'tablet' => '768px', 'desktop' => '1180px'];
    $deviceWidth = $deviceWidths[$device] ?? 'none';
    $deviceLabel = ['wide' => 'عریض', 'desktop' => 'دسکتاپ', 'tablet' => 'تبلت', 'mobile' => 'موبایل'][$device] ?? 'دسکتاپ';
    $resolvedPage = app(\Elearnac\PageBuilder\Services\ResponsiveSettings::class)->resolve($pageSettings, $device);
@endphp
<div class="pb-canvas mx-auto w-full" style="max-width: {{ $deviceWidth }};">
    <div class="mb-2 flex items-center justify-between px-1 text-[11px] text-slate-400">
        <span><i class="far fa-eye me-1"></i>پیشنمایش زنده — {{ $deviceLabel }}</span>
        <span>{{ count($document) }} المان در صفحه</span>
    </div>
    <div class="pb-page pb-device-{{ $device }} overflow-hidden rounded-lg border border-slate-200 shadow-sm dark:border-slate-800"
         data-page-builder-preview
         style="{{ app(\Elearnac\PageBuilder\Services\StyleCompiler::class)->pageVariables($pageSettings, $device) }};">

        @if ($resolvedPage['header'] ?? false)
            <div wire:ignore>
                @include(config('page-builder.preview_partials.header', 'general::layouts.partials.header'))
            </div>
        @endif

        <div class="pb-page-body min-h-[420px] p-2 sm:p-4">
            @if (count($document) === 0)
                @include('page-builder::builder.partials.drop-zone', [
                    'parent' => '',
                    'index' => 0,
                    'compact' => false,
                    'hint' => 'صفحه شما هنوز خالی است',
                ])
            @else
                @include('page-builder::builder.partials.drop-zone', [
                    'parent' => '',
                    'index' => 0,
                    'compact' => true,
                ])

                @foreach ($document as $index => $block)
                    @if (is_array($block) && isset($block['type']))
                        @include('page-builder::builder.partials.block-frame', [
                            'block' => $block,
                            'path' => (string) $index,
                            'parent' => '',
                            'index' => $index,
                        ])
                    @endif
                    @include('page-builder::builder.partials.drop-zone', [
                        'parent' => '',
                        'index' => $index + 1,
                        'compact' => true,
                    ])
                @endforeach
            @endif
        </div>

        @if ($resolvedPage['footer'] ?? false)
            <div wire:ignore>
                @include(config('page-builder.preview_partials.footer', 'general::layouts.partials.footer'))
            </div>
        @endif
    </div>
</div>
