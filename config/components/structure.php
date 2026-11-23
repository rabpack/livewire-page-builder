<?php

use Elearnac\PageBuilder\Support\ComponentBlueprint;
use Elearnac\PageBuilder\Support\Fields;

$layoutFields = Fields::merge(
    Fields::background(),
    Fields::border(12),
    Fields::spacing(18, 16),
    [
        'min_height' => Fields::number('حداقل ارتفاع (پیکسل)', 0, 'style', 0, 1200),
        'gap' => Fields::number('فاصله بین المانهای داخلی (پیکسل)', 12, 'style', 0, 96),
        'justify' => Fields::select('همترازی افقی', ['start' => 'ابتدا', 'center' => 'وسط', 'end' => 'انتها', 'between' => 'پراکنده'], 'start', 'style'),
        'container_width' => Fields::number('حداکثر عرض (پیکسل)', 1200, 'style', 320, 2200),
        'full_width' => Fields::switch('تمام عرض', false, 'style'),
        'classes' => Fields::text('کلاسهای اضافی', '', 'advanced'),
    ]
);

$columnFields = Fields::merge($layoutFields, [
    'column_span' => Fields::number('تعداد ستونها (از 12)', 6, 'style', 1, 12),
    'column_offset' => Fields::number('شروع از ستون', 0, 'style', 0, 11),
    'column_order' => Fields::number('ترتیب نمایش', 0, 'style', 0, 12),
]);

$gridFields = Fields::merge(
    $layoutFields,
    [
        'columns' => Fields::number('تعداد ستونها (از 12)', 12, 'style', 1, 12),
        'align_items' => Fields::select('همترازی عمودی', ['start' => 'بالا', 'center' => 'وسط', 'end' => 'پایین', 'stretch' => 'کشیده'], 'stretch', 'style'),
    ]
);

return [
    'container' => ComponentBlueprint::make('کانتینر', 'ساختار', 'far fa-square', 'layout', $layoutFields, ['variant' => 'container']),
    'section' => ComponentBlueprint::make('بخش', 'ساختار', 'far fa-object-group', 'layout', $layoutFields, ['variant' => 'section']),
    'inner-section' => ComponentBlueprint::make('بخش داخلی', 'ساختار', 'far fa-object-ungroup', 'layout', $layoutFields, ['variant' => 'inner-section']),
    'column' => ComponentBlueprint::make('ستون', 'ساختار', 'far fa-columns', 'layout', $columnFields, ['variant' => 'column']),
    'row' => ComponentBlueprint::make('ردیف', 'ساختار', 'far fa-grip-horizontal', 'layout', $gridFields, ['variant' => 'row']),
    'grid' => ComponentBlueprint::make('شبکه', 'ساختار', 'far fa-th', 'layout', $gridFields, ['variant' => 'grid']),
    'canvas' => ComponentBlueprint::make('بوم آزاد', 'ساختار', 'far fa-window-maximize', 'layout', $layoutFields, ['variant' => 'canvas']),
    'sidebar' => ComponentBlueprint::make('نوار کناری', 'ساختار', 'far fa-panel-left', 'layout', $layoutFields, ['variant' => 'sidebar']),
    'wrapper' => ComponentBlueprint::make('پوشش محتوا', 'ساختار', 'far fa-object-group', 'layout', $layoutFields, ['variant' => 'wrapper']),
    'spacer' => ComponentBlueprint::make('فاصله', 'ساختار', 'far fa-arrows-alt-v', 'spacing', [
        'height' => Fields::number('ارتفاع (پیکسل)', 40),
        'hide_mobile' => Fields::switch('پنهان در موبایل', false, 'advanced'),
    ], ['variant' => 'spacer']),
    'divider' => ComponentBlueprint::make('جداکننده', 'ساختار', 'far fa-minus', 'spacing', [
        'style' => Fields::select('سبک خط', ['solid' => 'پیوسته', 'dashed' => 'خطچین', 'dotted' => 'نقطهای', 'double' => 'دوتایی'], 'solid'),
        'color' => Fields::color('رنگ خط', '#cbd5e1'),
        'color_dark' => Fields::color('رنگ خط در حالت تاریک', '#475569'),
        'thickness' => Fields::number('ضخامت', 1),
        'width' => Fields::number('عرض (درصد)', 100),
        'margin' => Fields::number('فاصله عمودی', 16),
        'align' => Fields::alignment(),
    ], ['variant' => 'divider']),
    'menu-anchor' => ComponentBlueprint::make('لنگر منو', 'ساختار', 'far fa-anchor', 'button', [
        'anchor' => Fields::text('شناسه لنگر', 'section-1'),
        'label' => Fields::text('عنوان لنگر', 'بخش نخست'),
        'offset' => Fields::number('فاصله از بالا', 80),
        'classes' => Fields::text('کلاسهای اضافی', '', 'advanced'),
    ], ['variant' => 'anchor']),
];
