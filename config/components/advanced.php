<?php

use Elearnac\PageBuilder\Support\ComponentBlueprint;
use Elearnac\PageBuilder\Support\Fields;

return [
    'global-style' => ComponentBlueprint::make('سبک سراسری', 'پیشرفته', 'far fa-paint-brush', 'system', [
        'font' => Fields::font('فونت سراسری', 'site', 'advanced'),
        'accent' => Fields::color('رنگ اصلی', '#18b7a5', 'style'),
        'accent_dark' => Fields::color('رنگ اصلی در حالت تاریک', '#22d3ee', 'style'),
        'background' => Fields::color('پسزمینه', '#f8fafc'),
        'background_dark' => Fields::color('پسزمینه تاریک', '#020617'),
        'radius' => Fields::number('گردی پیشفرض', 12),
        'container_width' => Fields::number('عرض محتوا', 1200),
    ], ['variant' => 'global-style']),
    'custom-css' => ComponentBlueprint::make('CSS سفارشی', 'پیشرفته', 'far fa-code', 'system', [
        'css' => Fields::textarea('قواعد CSS', '.pb-my-class { color: #18b7a5; }', 'advanced'),
        'scope' => Fields::text('کلاس محدودکننده', 'pb-custom', 'advanced'),
    ], ['variant' => 'custom-css']),
    'conditions' => ComponentBlueprint::make('شرط نمایش', 'پیشرفته', 'far fa-code-branch', 'system', [
        'condition' => Fields::select('شرط', ['always' => 'همیشه', 'logged_in' => 'کاربر واردشده', 'guest' => 'مهمان', 'course_enrolled' => 'ثبتنامشده در دوره'], 'always', 'advanced'),
        'course_id' => Fields::text('شناسه دوره', ''),
        'fallback' => Fields::textarea('محتوی جایگزین', ''),
    ], ['variant' => 'conditions']),
    'dynamic-tag' => ComponentBlueprint::make('تگ پویا', 'پیشرفته', 'far fa-code', 'text', [
        'tag' => Fields::select('تگ', ['site_name' => 'نام سایت', 'page_title' => 'عنوان صفحه', 'user_name' => 'نام کاربر', 'date' => 'تاریخ امروز'], 'site_name'),
        'prefix' => Fields::text('پیشوند', ''),
        'suffix' => Fields::text('پسوند', ''),
    ], ['variant' => 'dynamic-tag']),
    'raw-html' => ComponentBlueprint::make('HTML امن', 'پیشرفته', 'far fa-file-code', 'text', [
        'body' => Fields::textarea('کد HTML (پاکسازی میشود)', '<p>محتوای دلخواه</p>'),
    ], ['variant' => 'html']),
    'sticky' => ComponentBlueprint::make('چسبنده', 'پیشرفته', 'far fa-arrow-up', 'system', Fields::merge(Fields::background(), Fields::border(10), ['position' => Fields::select('محل چسبیدن', ['top' => 'بالا', 'bottom' => 'پایین'], 'bottom'), 'offset' => Fields::number('فاصله', 16), 'body' => Fields::editor('محتوا', 'محتوای چسبنده')]), ['variant' => 'sticky']),
    'popup-trigger' => ComponentBlueprint::make('ماشه پاپآپ', 'پیشرفته', 'far fa-window-restore', 'system', Fields::merge(Fields::typography(14, '#334155'), ['label' => Fields::text('متن دکمه', 'باز کردن پاپآپ'), 'popup_id' => Fields::text('شناسه پاپآپ', 'popup-1'), 'delay' => Fields::number('تاخیر خودکار (ثانیه)', 0, 'advanced', 0, 120)]), ['variant' => 'popup']),
    'table-of-contents' => ComponentBlueprint::make('فهرست محتوا', 'پیشرفته', 'far fa-list-ol', 'navigation', Fields::merge(Fields::background(), Fields::border(10), Fields::typography(14, '#334155'), ['title' => Fields::text('عنوان', 'فهرست مطالب'), 'depth' => Fields::number('عمق عناوین', 3, 'content', 1, 6), 'sticky' => Fields::switch('چسبنده', false, 'advanced')]), ['variant' => 'toc']),
    'google-map' => ComponentBlueprint::make('نقشه گوگل', 'پیشرفته', 'fab fa-google', 'map', Fields::merge(Fields::border(12), ['address' => Fields::text('آدرس', 'تهران، ایران'), 'zoom' => Fields::number('بزرگنمایی', 14), 'height' => Fields::number('ارتفاع', 360)]), ['variant' => 'google-map']),
    'login-gate-pro' => ComponentBlueprint::make('دروازه ورود پیشرفته', 'پیشرفته', 'far fa-lock', 'system', Fields::merge(Fields::background('#0f172acc', '#020617cc'), Fields::border(12), ['message' => Fields::text('پیام', 'برای مشاهده وارد شوید.'), 'button' => Fields::text('متن دکمه', 'ورود به سایت')]), ['variant' => 'login-gate']),
    'breadcrumbs' => ComponentBlueprint::make('مسیر صفحه پیشرفته', 'پیشرفته', 'far fa-route', 'navigation', Fields::merge(Fields::typography(13, '#64748b'), ['home' => Fields::text('خانه', 'خانه'), 'separator' => Fields::text('جداکننده', '‹'), 'schema' => Fields::switch('افزودن نشانهگذاری ساختاریافته', true, 'advanced')]), ['variant' => 'breadcrumb']),
];
