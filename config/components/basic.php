<?php

use Elearnac\PageBuilder\Support\ComponentBlueprint;
use Elearnac\PageBuilder\Support\Fields;

$contentFields = Fields::merge(
    ['body' => Fields::editor('محتوا', 'متن خود را وارد کنید.')],
    Fields::typography(16, '#334155'),
    ['classes' => Fields::text('کلاسهای اضافی', '', 'advanced')]
);

return [
    'heading' => ComponentBlueprint::make('عنوان', 'عمومی', 'far fa-heading', 'heading', Fields::merge(
        ['title' => Fields::text('متن عنوان', 'عنوان جدید'), 'subtitle' => Fields::text('زیرعنوان', '')],
        Fields::typography(32, '#0f172a'),
        ['level' => Fields::select('سطح عنوان', ['h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6'], 'h2'), 'margin' => Fields::number('فاصله خارجی', 12), 'classes' => Fields::text('کلاسهای اضافی', '', 'advanced')]
    ), ['variant' => 'heading']),
    'text-editor' => ComponentBlueprint::make('ویرایشگر متن', 'عمومی', 'far fa-paragraph', 'text', $contentFields, ['variant' => 'text']),
    'button' => ComponentBlueprint::make('دکمه', 'عمومی', 'far fa-mouse-pointer', 'button', Fields::merge(
        ['label' => Fields::text('متن دکمه', 'ادامه مطلب'), 'link' => Fields::link('لینک', '/'), 'target' => Fields::select('نحوه باز شدن', ['_self' => 'همان صفحه', '_blank' => 'صفحه جدید'], '_self', 'advanced')],
        Fields::typography(15, '#ffffff'),
        Fields::background('#18b7a5', '#0f3d6e'),
        Fields::border(10),
        ['style' => Fields::select('سبک دکمه', ['solid' => 'پر', 'outline' => 'خطی', 'ghost' => 'ساده'], 'solid'), 'icon' => Fields::icon('آیکون دکمه', ''), 'button_size' => Fields::select('اندازه دکمه', ['sm' => 'کوچک', 'md' => 'متوسط', 'lg' => 'بزرگ'], 'md', 'style'), 'block' => Fields::switch('تمام عرض', false, 'style')]
    ), ['variant' => 'button']),
    'read-more' => ComponentBlueprint::make('ادامه مطلب', 'عمومی', 'far fa-ellipsis', 'button', Fields::merge(
        ['label' => Fields::text('متن دکمه', 'ادامه مطلب'), 'link' => Fields::link('لینک', '#')],
        Fields::typography(14, '#18b7a5'),
        ['style' => Fields::select('سبک', ['solid' => 'پر', 'outline' => 'خطی', 'ghost' => 'ساده'], 'outline')]
    ), ['variant' => 'read-more']),
    'icon' => ComponentBlueprint::make('آیکون', 'عمومی', 'far fa-star', 'icon', Fields::merge(
        ['icon' => Fields::icon('آیکون', 'far fa-star'), 'link' => Fields::link('لینک', '')],
        Fields::typography(30, '#18b7a5'),
        Fields::border(8)
    ), ['variant' => 'icon']),
    'icon-box' => ComponentBlueprint::make('جعبه آیکون', 'عمومی', 'far fa-square', 'icon', Fields::merge(
        ['icon' => Fields::icon('آیکون', 'far fa-graduation-cap'), 'title' => Fields::text('عنوان', 'یادگیری حرفهای'), 'body' => Fields::textarea('توضیحات', ''), 'link' => Fields::link('لینک', '#')],
        Fields::typography(16, '#334155'),
        Fields::background(),
        Fields::border(14),
        Fields::spacing(20, 10)
    ), ['variant' => 'icon-box']),
    'image-box' => ComponentBlueprint::make('جعبه تصویر', 'عمومی', 'far fa-image', 'icon', Fields::merge(
        ['image' => Fields::files('تصویر', []), 'title' => Fields::text('عنوان', 'عنوان تصویر'), 'body' => Fields::textarea('توضیحات', ''), 'link' => Fields::link('لینک', '#')],
        Fields::typography(16, '#334155'),
        Fields::background(),
        Fields::border(14)
    ), ['variant' => 'image-box']),
    'list' => ComponentBlueprint::make('لیست', 'عمومی', 'far fa-list', 'list', Fields::merge(
        ['items' => Fields::repeater('موارد لیست', ['title' => 'text', 'body' => 'textarea', 'icon' => 'text'], [['title' => 'مزایای دوره', 'body' => '', 'icon' => 'far fa-check']]), 'icon' => Fields::icon('آیکون پیشفرض', 'far fa-check')],
        Fields::typography(15, '#334155'),
        Fields::background(),
        Fields::border(12)
    ), ['variant' => 'list']),
    'alert' => ComponentBlueprint::make('هشدار', 'عمومی', 'far fa-exclamation-triangle', 'alert', Fields::merge(
        ['type' => Fields::select('نوع هشدار', ['info' => 'اطلاعات', 'success' => 'موفقیت', 'warning' => 'هشدار', 'danger' => 'خطا'], 'info'), 'title' => Fields::text('عنوان', 'توجه'), 'body' => Fields::textarea('متن', 'متن هشدار را وارد کنید.'), 'dismissible' => Fields::switch('قابل بسته شدن', true, 'advanced')],
        Fields::typography(14, '#334155'),
        Fields::border(10)
    ), ['variant' => 'alert']),
    'counter' => ComponentBlueprint::make('شمارشگر', 'عمومی', 'far fa-sort-numeric-up', 'counter', Fields::merge(
        ['number' => Fields::number('عدد', 1200), 'label' => Fields::text('عنوان', 'دانشجو'), 'prefix' => Fields::text('پیشوند', ''), 'suffix' => Fields::text('پسوند', '+')],
        Fields::typography(32, '#0f3d6e'),
        ['duration' => Fields::number('مدت انیمیشن (میلیثانیه)', 1400), 'animate' => Fields::switch('انیمیشن', true, 'advanced')]
    ), ['variant' => 'counter']),
    'progress' => ComponentBlueprint::make('نوار پیشرفت', 'عمومی', 'far fa-chart-bar', 'counter', Fields::merge(
        ['label' => Fields::text('عنوان', 'پیشرفت'), 'value' => Fields::number('درصد', 75, 'content', 0, 100), 'show_percent' => Fields::switch('نمایش درصد', true)],
        Fields::typography(14, '#334155'),
        ['height' => Fields::number('ارتفاع نوار', 10), 'striped' => Fields::switch('خطدار', false, 'style'), 'accent' => Fields::color('رنگ نوار', '#18b7a5')]
    ), ['variant' => 'progress']),
    'star-rating' => ComponentBlueprint::make('امتیاز ستارهای', 'عمومی', 'far fa-star', 'counter', Fields::merge(
        ['value' => Fields::number('امتیاز (۱ تا ۵)', 4, 'content', 1, 5), 'count' => Fields::number('تعداد آرا', 128), 'label' => Fields::text('عنوان', 'امتیاز دانشجویان'), 'show_count' => Fields::switch('نمایش تعداد', true)],
        Fields::typography(14, '#334155'),
        ['color' => Fields::color('رنگ ستاره', '#f59e0b')]
    ), ['variant' => 'rating']),
    'html' => ComponentBlueprint::make('HTML', 'عمومی', 'far fa-code', 'text', Fields::merge(
        ['body' => Fields::textarea('کد HTML (فقط تگهای مجاز)', '<p>محتوای دلخواه</p>')],
        Fields::typography(14, '#334155')
    ), ['variant' => 'html']),
    'shortcode' => ComponentBlueprint::make('شورتکد', 'عمومی', 'far fa-code-branch', 'text', [
        'shortcode' => Fields::text('شورتکد', '[form]'),
        'body' => Fields::textarea('جایگزین', 'شورتکدی برای نمایش وجود ندارد.'),
    ], ['variant' => 'shortcode']),
    'code' => ComponentBlueprint::make('کد', 'عمومی', 'far fa-file-code', 'text', [
        'body' => Fields::textarea('کد', 'echo "Hello";'),
        'language' => Fields::text('زبان', 'php'),
    ], ['variant' => 'code']),
];
