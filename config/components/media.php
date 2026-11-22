<?php

use Elearnac\PageBuilder\Support\ComponentBlueprint;
use Elearnac\PageBuilder\Support\Fields;

$mediaBase = Fields::merge(
    Fields::background('#0f172a', '#020617'),
    Fields::border(16),
    Fields::spacing(0, 0),
    [
        'caption' => Fields::text('زیرنویس', ''),
        'alt' => Fields::text('متن جایگزین تصویر', ''),
        'classes' => Fields::text('کلاسهای اضافی', '', 'advanced'),
    ]
);

return [
    'image-gallery' => ComponentBlueprint::make('گالری تصاویر', 'رسانه', 'far fa-images', 'gallery', Fields::merge(
        ['images' => Fields::files('تصاویر از مدارک سایت', []), 'source' => Fields::source('static'), 'category_id' => Fields::model(), 'limit' => Fields::number('تعداد', 8, 'content', 1, 100), 'lightbox' => Fields::switch('نمایش بزرگ', true, 'advanced'), 'show_title' => Fields::switch('نمایش عنوان', true)],
        Fields::border(14), Fields::spacing(12, 12), Fields::slider(), ['columns' => Fields::number('ستونها', 4)]
    ), ['variant' => 'gallery']),
    'media-carousel' => ComponentBlueprint::make('اسلایدر رسانه', 'رسانه', 'far fa-film', 'gallery', Fields::merge(
        ['images' => Fields::files('تصاویر', []), 'source' => Fields::source('static'), 'effect' => Fields::select('جلوه', ['slide' => 'کشویی', 'fade' => 'محو', 'cards' => 'کارتها', 'coverflow' => 'سهبعدی'], 'slide')],
        Fields::border(16), Fields::slider()
    ), ['variant' => 'carousel']),
    'slides' => ComponentBlueprint::make('اسلایدها', 'رسانه', 'far fa-images', 'gallery', Fields::merge(
        ['images' => Fields::files('اسلایدها', []), 'effect' => Fields::select('جلوه', ['slide' => 'کشویی', 'fade' => 'محو', 'cards' => 'کارتها'], 'slide')],
        Fields::border(16), Fields::slider()
    ), ['variant' => 'slides']),
    'video' => ComponentBlueprint::make('ویدیو', 'رسانه', 'far fa-video', 'media', Fields::merge(
        $mediaBase,
        ['url' => Fields::text('لینک ویدیو (MP4، HLS یا YouTube)', ''), 'poster' => Fields::files('تصویر پوستر', []), 'controls' => Fields::switch('کنترلها', true), 'autoplay' => Fields::switch('پخش خودکار', false, 'advanced'), 'loop' => Fields::switch('تکرار', false, 'advanced'), 'muted' => Fields::switch('بیصدا', false, 'advanced'), 'ratio' => Fields::select('نسبت تصویر', ['16/9' => '۱۶:۹', '4/3' => '۴:۳', '1/1' => 'مربع'], '16/9')]
    ), ['variant' => 'video']),
    'video-player' => ComponentBlueprint::make('پخشکننده درس', 'رسانه', 'far fa-play-circle', 'media', Fields::merge(
        $mediaBase,
        ['source' => Fields::source('lesson'), 'limit' => Fields::number('تعداد درسها', 1), 'show_playlist' => Fields::switch('نمایش لیست درسها', true), 'auto_next' => Fields::switch('پخش درس بعدی', true, 'advanced')]
    ), ['variant' => 'player']),
    'audio' => ComponentBlueprint::make('صوت', 'رسانه', 'far fa-volume-up', 'media', Fields::merge(
        Fields::background('#f8fafc', '#0f172a'), Fields::border(12),
        ['url' => Fields::text('لینک فایل صوتی', ''), 'title' => Fields::text('عنوان', ''), 'controls' => Fields::switch('کنترلها', true)]
    ), ['variant' => 'audio']),
    'audio-playlist' => ComponentBlueprint::make('لیست پخش صوت', 'رسانه', 'far fa-list-alt', 'media', Fields::merge(
        Fields::background(), Fields::border(12),
        ['items' => Fields::repeater('آهنگها', ['title' => 'text', 'url' => 'text'], []), 'compact' => Fields::switch('حالت فشرده', false)]
    ), ['variant' => 'audio-list']),
    'youtube' => ComponentBlueprint::make('یوتیوب', 'رسانه', 'fab fa-youtube', 'media', Fields::merge($mediaBase, ['url' => Fields::text('لینک ویدیو یوتیوب', ''), 'privacy' => Fields::switch('حالت حریم خصوصی', true, 'advanced')]), ['variant' => 'youtube']),
    'vimeo' => ComponentBlueprint::make('ویمئو', 'رسانه', 'fab fa-vimeo-v', 'media', Fields::merge($mediaBase, ['url' => Fields::text('لینک ویدیو ویمئو', '')]), ['variant' => 'vimeo']),
    'lightbox' => ComponentBlueprint::make('نمایش بزرگ تصویر', 'رسانه', 'far fa-expand', 'gallery', Fields::merge(
        ['image' => Fields::files('تصویر', []), 'thumbnail' => Fields::files('تصویر کوچک', []), 'caption' => Fields::text('زیرنویس', '')],
        Fields::border(12)
    ), ['variant' => 'lightbox']),
    'image-compare' => ComponentBlueprint::make('مقایسه تصاویر', 'رسانه', 'far fa-columns', 'media', Fields::merge(
        Fields::border(12),
        ['before' => Fields::files('تصویر قبل', []), 'after' => Fields::files('تصویر بعد', []), 'position' => Fields::number('موقعیت خط جداکننده (درصد)', 50, 'content', 0, 100), 'label_before' => Fields::text('برچسب قبل', 'قبل'), 'label_after' => Fields::text('برچسب بعد', 'بعد')]
    ), ['variant' => 'compare']),
    'before-after' => ComponentBlueprint::make('قبل و بعد', 'رسانه', 'far fa-adjust', 'media', Fields::merge(Fields::border(12), ['before' => Fields::files('تصویر قبل', []), 'after' => Fields::files('تصویر بعد', [])]), ['variant' => 'compare']),
    'hotspot' => ComponentBlueprint::make('نقاط داغ', 'رسانه', 'far fa-map-marker-alt', 'media', Fields::merge(
        Fields::border(12),
        ['image' => Fields::files('تصویر', []), 'items' => Fields::repeater('نقاط', ['x' => 'text', 'y' => 'text', 'title' => 'text', 'body' => 'textarea'], [['x' => '50', 'y' => '50', 'title' => 'نقطه اطلاعاتی', 'body' => 'توضیحات نقطه']])]
    ), ['variant' => 'hotspot']),
    'lottie' => ComponentBlueprint::make('انیمیشن Lottie', 'رسانه', 'far fa-bolt', 'media', Fields::merge(Fields::border(12), ['url' => Fields::text('لینک JSON انیمیشن', ''), 'loop' => Fields::switch('تکرار', true), 'autoplay' => Fields::switch('پخش خودکار', true)]), ['variant' => 'lottie']),
    'map' => ComponentBlueprint::make('نقشه', 'رسانه', 'far fa-map', 'map', Fields::merge(Fields::border(12), ['latitude' => Fields::text('عرض جغرافیایی', '35.6892'), 'longitude' => Fields::text('طول جغرافیایی', '51.3890'), 'zoom' => Fields::number('بزرگنمایی', 14), 'height' => Fields::number('ارتفاع', 360), 'provider' => Fields::select('سرویس نقشه', ['openstreetmap' => 'OpenStreetMap', 'google' => 'Google Maps'], 'openstreetmap')]), ['variant' => 'map']),
    'openstreetmap' => ComponentBlueprint::make('نقشه آزاد', 'رسانه', 'far fa-map-marked', 'map', Fields::merge(Fields::border(12), ['latitude' => Fields::text('عرض جغرافیایی', '35.6892'), 'longitude' => Fields::text('طول جغرافیایی', '51.3890'), 'zoom' => Fields::number('بزرگنمایی', 14), 'height' => Fields::number('ارتفاع', 360)]), ['variant' => 'map']),
    'social-icons' => ComponentBlueprint::make('شبکههای اجتماعی', 'رسانه', 'far fa-share-alt', 'social', Fields::merge(Fields::typography(18, '#0f172a'), ['items' => Fields::repeater('شبکهها', ['network' => 'text', 'url' => 'text'], [['network' => 'instagram', 'url' => '#'], ['network' => 'telegram', 'url' => '#']]), 'shape' => Fields::select('شکل', ['circle' => 'دایره', 'square' => 'مربع'], 'circle')]), ['variant' => 'social']),
    'social-share' => ComponentBlueprint::make('اشتراکگذاری', 'رسانه', 'far fa-share-square', 'social', Fields::merge(Fields::typography(14, '#334155'), ['networks' => Fields::repeater('شبکهها', ['network' => 'text'], [['network' => 'telegram'], ['network' => 'whatsapp']]), 'label' => Fields::text('عنوان', 'اشتراکگذاری')]), ['variant' => 'share']),
    'facebook-page' => ComponentBlueprint::make('صفحه فیسبوک', 'رسانه', 'fab fa-facebook', 'social', ['url' => Fields::text('لینک صفحه', ''), 'height' => Fields::number('ارتفاع', 500)], ['variant' => 'embed']),
    'instagram-feed' => ComponentBlueprint::make('فید اینستاگرام', 'رسانه', 'fab fa-instagram', 'social', ['username' => Fields::text('نام کاربری', ''), 'limit' => Fields::number('تعداد', 8), 'columns' => Fields::number('ستونها', 4)], ['variant' => 'embed']),
    'tiktok-feed' => ComponentBlueprint::make('فید تیکتاک', 'رسانه', 'fab fa-tiktok', 'social', ['username' => Fields::text('نام کاربری', ''), 'limit' => Fields::number('تعداد', 6)], ['variant' => 'embed']),
];
