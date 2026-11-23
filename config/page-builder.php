<?php

use Elearnac\PageBuilder\Support\ComponentBlueprint;
use Elearnac\PageBuilder\Support\Fields;

return [
    'framework' => env('PAGE_BUILDER_FRAMEWORK', 'tailwind'),

    'front_layout' => 'general::layouts.layout',
    'preview_partials' => [
        'header' => 'general::layouts.partials.header',
        'footer' => 'general::layouts.partials.footer',
    ],

    'framework_classes' => [
        'tailwind' => [
            'container' => 'mx-auto w-full max-w-6xl px-4',
            'section' => 'py-10',
            'card' => 'rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900',
        ],
        'bootstrap' => [
            'container' => 'container mx-auto',
            'section' => 'py-5',
            'card' => 'card',
        ],
    ],

    'model' => \Elearnac\PageBuilder\Models\BuilderPage::class,
    'document_column' => 'document',
    'settings_column' => 'settings',
    'edit_route' => null,

    'file_model' => \Modules\File\App\Models\File::class,
    'file_manager' => [
        'component' => 'file::file-manager.file-manager',
        'name' => 'pb-file',
    ],

    'catalog' => [
        'category_model' => \Modules\Category\App\Models\Category::class,
        'category_label' => 'title',
        'item_model' => \Modules\Course\App\Models\Course::class,
        'item_label' => 'title',
        'item_image' => 'poster_id',
        'category_key' => 'category_id',
    ],

    'gallery' => [
        'item_model' => \Modules\Course\App\Models\Course::class,
        'item_label' => 'title',
        'item_image' => 'poster_id',
        'category_model' => \Modules\Category\App\Models\Category::class,
        'category_label' => 'title',
        'category_key' => 'category_id',
    ],

    'reviews' => [
        'model' => \Modules\Comment\App\Models\Comment::class,
        'label' => 'title',
    ],

    'components' => array_replace_recursive(
        require __DIR__.'/components/structure.php',
        require __DIR__.'/components/basic.php',
        require __DIR__.'/components/media.php',
        require __DIR__.'/components/content.php',
        require __DIR__.'/components/forms.php',
        require __DIR__.'/components/dynamic.php',
        require __DIR__.'/components/user.php',
        require __DIR__.'/components/ecommerce.php',
        require __DIR__.'/components/advanced.php',
        [
            'heading' => ComponentBlueprint::make('عنوان', 'عمومی', 'far fa-heading', 'heading', Fields::merge(
                ['title' => Fields::text('متن عنوان', 'عنوان جدید'), 'subtitle' => Fields::text('زیرعنوان', '')],
                Fields::typography(32, '#0f172a'),
                ['level' => Fields::select('سطح عنوان', ['h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6'], 'h2'), 'margin' => Fields::number('فاصله خارجی', 12), 'classes' => Fields::text('کلاسهای اضافی', '', 'advanced')]
            ), ['variant' => 'heading']),
            'text' => ComponentBlueprint::make('متن', 'عمومی', 'far fa-paragraph', 'text', Fields::merge(
                ['body' => Fields::editor('متن', 'متن خود را وارد کنید.')],
                Fields::typography(16, '#334155'),
                ['tag' => Fields::select('تگ HTML', ['p' => 'پاراگراف', 'div' => 'بلوک', 'blockquote' => 'نقل قول'], 'p', 'advanced'), 'classes' => Fields::text('کلاسهای اضافی', '', 'advanced')]
            ), ['variant' => 'text']),
            'image-slider' => ComponentBlueprint::make('اسلاید تصاویر', 'رسانه', 'far fa-images', 'gallery', Fields::merge(
                Fields::background(), Fields::border(16), Fields::spacing(0, 0),
                [
                    'images' => Fields::files('تصاویر (از مدارک سایت)', []),
                    'effect' => Fields::select('جلوه', ['slide' => 'کشویی', 'fade' => 'محو', 'cards' => 'کارتها', 'coverflow' => 'نمای سهبعدی'], 'slide'),
                    'height' => Fields::number('ارتفاع (پیکسل)', 360, 'style', 120, 1200),
                    'image_fit' => Fields::select('نحوه نمایش تصویر', ['cover' => 'پر کردن', 'contain' => 'کامل'], 'cover', 'style'),
                ],
                Fields::slider()
            ), ['variant' => 'carousel']),
            'course-slider' => ComponentBlueprint::make('اسلاید دوره/محصول', 'دادهها', 'far fa-graduation-cap', 'dynamic', Fields::merge(
                Fields::background(), Fields::border(16), Fields::spacing(16, 16), Fields::typography(15, '#334155'),
                [
                    'source' => Fields::source('course'), 'category_id' => Fields::model(), 'limit' => Fields::number('تعداد', 8, 'content', 1, 100),
                    'show_image' => Fields::switch('نمایش تصویر', true), 'show_title' => Fields::switch('نمایش عنوان', true),
                    'show_text' => Fields::switch('نمایش توضیحات', true), 'show_price' => Fields::switch('نمایش قیمت', true),
                    'show_meta' => Fields::switch('نمایش فراداده', true),
                ],
                Fields::slider()
            ), ['variant' => 'course', 'source' => 'course']),
            'reviews' => ComponentBlueprint::make('نظرات', 'دادهها', 'far fa-star', 'commerce', Fields::merge(
                Fields::background(), Fields::border(14), Fields::spacing(16, 12), Fields::typography(14, '#334155'),
                [
                    'source' => Fields::source('comment'), 'limit' => Fields::number('تعداد', 6, 'content', 1, 50), 'columns' => Fields::number('ستونها', 3),
                    'show_rating' => Fields::switch('نمایش امتیاز', true), 'show_form' => Fields::switch('نمایش فرم نظر', false),
                ]
            ), ['variant' => 'reviews', 'source' => 'comment']),
            'login-gate' => ComponentBlueprint::make('دروازه ورود', 'پیشرفته', 'far fa-lock', 'system', Fields::merge(
                Fields::background('#0f172acc', '#020617cc'), Fields::border(12), Fields::typography(14, '#ffffff'),
                ['message' => Fields::text('پیام', 'برای مشاهده این بخش وارد شوید.'), 'button' => Fields::text('متن دکمه', 'ورود به سایت'), 'body' => Fields::editor('محتوای محافظت شده', 'محتوای دوره')]
            ), ['variant' => 'login-gate']),
            'gallery' => ComponentBlueprint::make('گالری تصاویر', 'رسانه', 'far fa-border-all', 'gallery', Fields::merge(
                Fields::background(), Fields::border(14), Fields::spacing(16, 12),
                [
                    'source' => Fields::select('منبع', ['product' => 'محصولات/دورهها', 'manual' => 'انتخاب دستی'], 'product'),
                    'category_id' => Fields::model(), 'limit' => Fields::number('تعداد', 8, 'content', 1, 100),
                    'images' => Fields::files('تصاویر دستی (از مدارک سایت)', []),
                    'columns' => Fields::number('ستونها', 4, 'style', 1, 8), 'gap' => Fields::number('فاصله', 12, 'style'),
                    'image_fit' => Fields::select('نحوه نمایش', ['cover' => 'پر کردن', 'contain' => 'کامل'], 'cover', 'style'),
                    'lightbox' => Fields::switch('نمایش بزرگ با کلیک', true, 'advanced'), 'show_title' => Fields::switch('نمایش عنوان', true),
                ]
            ), ['variant' => 'grid', 'source' => 'product']),
            'image' => ComponentBlueprint::make('تصویر', 'رسانه', 'far fa-image', 'icon', Fields::merge(
                Fields::border(12), Fields::typography(14, '#334155'),
                [
                    'image' => Fields::files('تصویر (از مدارک سایت)', []), 'alt' => Fields::text('متن جایگزین', ''),
                    'caption' => Fields::text('زیرنویس', ''), 'link' => Fields::link('لینک', ''),
                    'height' => Fields::number('ارتفاع', 280, 'style', 80, 1200), 'fit' => Fields::select('نحوه نمایش', ['cover' => 'پر کردن', 'contain' => 'کامل'], 'cover', 'style'),
                ]
            ), ['variant' => 'featured-image']),
            'pricing' => ComponentBlueprint::make('جدول قیمت', 'دادهها', 'far fa-tag', 'commerce', Fields::merge(
                Fields::background(), Fields::border(14), Fields::spacing(18, 12), Fields::typography(16, '#334155'),
                [
                    'plan' => Fields::text('نام پلن', 'پلن حرفهای'), 'price' => Fields::text('قیمت', '990,000'), 'period' => Fields::text('دوره', 'ماهانه'),
                    'features' => Fields::repeater('مزایا', ['title' => 'text'], [['title' => 'پشتیبانی کامل'], ['title' => 'دسترسی نامحدود']]),
                    'button' => Fields::text('متن دکمه', 'خرید پلن'), 'link' => Fields::link('لینک دکمه', '#'), 'highlight' => Fields::switch('برجسته', true, 'style'),
                ]
            ), ['variant' => 'pricing-table']),
        ]
    ),

    'fonts' => [
        'default' => 'site',
        'stylesheets' => [],
        'families' => [
            'site' => ['label' => 'فونت سایت', 'face' => 'iransans', 'family' => '"iransans", system-ui, sans-serif'],
            'yekan' => ['label' => 'یکان باخ', 'face' => 'Yekan Bakh FaNum', 'family' => '"Yekan Bakh FaNum", system-ui, sans-serif'],
            'bold' => ['label' => 'فونت ضخیم سایت', 'face' => 'bold-font', 'family' => '"bold-font", sans-serif'],
            'system' => ['label' => 'سیستمی', 'family' => 'system-ui, sans-serif'],
        ],
    ],

    'data_sources' => [
        'course' => ['model' => \Elearnac\PageBuilder\Models\BuilderPage::class, 'title' => 'title', 'text' => 'description', 'image' => 'poster_id', 'category_key' => 'category_id', 'route' => 'page-builder.preview'],
        'post' => ['model' => \Elearnac\PageBuilder\Models\BuilderPage::class, 'title' => 'title', 'text' => 'description', 'image' => 'poster_id'],
        'product' => ['model' => \Elearnac\PageBuilder\Models\BuilderPage::class, 'title' => 'title', 'text' => 'description', 'image' => 'poster_id'],
        'page' => ['model' => \Elearnac\PageBuilder\Models\BuilderPage::class, 'title' => 'title', 'text' => 'description'],
        'category' => ['model' => \Elearnac\PageBuilder\Models\BuilderPage::class, 'title' => 'title'],
        'user' => ['model' => \App\Models\User::class, 'title' => 'name', 'text' => 'email'],
        'comment' => ['model' => \Elearnac\PageBuilder\Models\BuilderPage::class, 'title' => 'title', 'text' => 'body'],
        'lesson' => ['model' => \Elearnac\PageBuilder\Models\BuilderPage::class, 'title' => 'title'],
        'exam' => ['model' => \Elearnac\PageBuilder\Models\BuilderPage::class, 'title' => 'title'],
        'order' => ['model' => \Elearnac\PageBuilder\Models\BuilderPage::class, 'title' => 'id'],
    ],

    'editor' => [
        'component' => null,
        'sanitizer' => null,
        'toolbar' => ['bold', 'italic', '|', 'link', 'bulletedList', 'numberedList', '|', 'sourceEditing'],
    ],

    'submissions' => [
        'layout' => 'general::layouts.layout',
        'permission' => null,
        'middleware' => ['web', 'auth'],
    ],

    'templates' => [
        'landing' => [
            'label' => 'صفحه فرود',
            'description' => 'عنوان، متن، دکمه، مزایا و فرم تماس',
            'blocks' => [
                ['type' => 'heading', 'settings' => ['title' => 'با ما بهترینها را بیاموزید', 'level' => 'h1', 'align' => 'center', 'size' => 40]],
                ['type' => 'text', 'settings' => ['body' => 'دورههای حرفهای ما را با پشتیبانی کامل و گواهینامه معتبر شروع کنید.', 'align' => 'center', 'size' => 'lg']],
                ['type' => 'button', 'settings' => ['label' => 'مشاهده دورهها', 'link' => '/course', 'align' => 'center', 'style' => 'solid']],
                ['type' => 'pricing', 'settings' => ['plan' => 'پلن ویژه', 'price' => '990,000', 'highlight' => true]],
                ['type' => 'form', 'settings' => ['title' => 'در تماس باشید']],
            ],
        ],
        'course' => [
            'label' => 'معرفی دوره',
            'description' => 'اسلاید دوره، آمار، زبانهها و نظرات',
            'blocks' => [
                ['type' => 'heading', 'settings' => ['title' => 'دوره آکادمی ما', 'align' => 'center', 'size' => 36]],
                ['type' => 'course-slider', 'settings' => ['limit' => 6, 'autoplay' => true]],
                ['type' => 'counter', 'settings' => ['number' => 1200, 'label' => 'دانشجوی راضی']],
                ['type' => 'tabs'],
                ['type' => 'reviews', 'settings' => ['layout' => 'grid', 'limit' => 6]],
            ],
        ],
        'faq' => [
            'label' => 'سوالات متداول',
            'description' => 'هشدار، آکاردئون و فرم پیگیری',
            'blocks' => [
                ['type' => 'heading', 'settings' => ['title' => 'سوالات متداول', 'align' => 'center']],
                ['type' => 'alert', 'settings' => ['type' => 'info', 'title' => 'پیش از شروع', 'body' => 'پاسخ سوالات خود را در ادامه پیدا کنید.']],
                ['type' => 'accordion'],
                ['type' => 'spacer', 'settings' => ['height' => 24]],
                ['type' => 'form', 'settings' => ['title' => 'سوال دیگری دارید؟']],
            ],
        ],
    ],

];
