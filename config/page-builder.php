<?php

return [
    /*
     * CSS framework used by the builder and rendered pages. Tailwind is the
     * default; set to 'bootstrap' (or your own key) to swap the wrapper classes.
     */
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

    /* Page model and the column that stores the builder document. */
    'model' => \Elearnac\PageBuilder\Models\BuilderPage::class,
    'document_column' => 'document',

    /*
     * Categories for the courses/products slider. Point these at your own
     * category table and item table in any project.
     */
    'catalog' => [
        'category_model' => \Modules\Category\App\Models\Category::class,
        'category_label' => 'title',
        'item_model' => \Modules\Course\App\Models\Course::class,
        'item_label' => 'title',
        'item_image' => 'poster_id',
        'category_key' => 'category_id',
    ],

    /* Reviews source for the reviews component. */
    'reviews' => [
        'model' => \Modules\Comment\App\Models\Comment::class,
        'label' => 'title',
    ],

    /* Every component is registered here. Add your own class + fields to extend. */
    'components' => [
        'container' => [
            'label' => 'کانتینر',
            'icon' => 'far fa-square',
            'class' => \Elearnac\PageBuilder\Livewire\Components\ContainerBlock::class,
            'fields' => [
                'background' => ['type' => 'color', 'label' => 'پسزمینه', 'default' => '#ffffff'],
                'padding' => ['type' => 'number', 'label' => 'پدینگ (پیکسل)', 'default' => 24],
                'radius' => ['type' => 'number', 'label' => 'گردی گوشهها', 'default' => 14],
                'classes' => ['type' => 'text', 'label' => 'کلاسهای اضافی', 'default' => ''],
            ],
        ],
        'grid' => [
            'label' => 'شبکه',
            'icon' => 'far fa-grip',
            'class' => \Elearnac\PageBuilder\Livewire\Components\GridBlock::class,
            'fields' => [
                'columns' => ['type' => 'number', 'label' => 'تعداد ستونها', 'default' => 2],
                'gap' => ['type' => 'number', 'label' => 'فاصله (پیکسل)', 'default' => 16],
                'background' => ['type' => 'color', 'label' => 'پسزمینه', 'default' => '#ffffff'],
                'classes' => ['type' => 'text', 'label' => 'کلاسهای اضافی', 'default' => ''],
            ],
        ],
        'heading' => [
            'label' => 'عنوان',
            'icon' => 'far fa-heading',
            'class' => \Elearnac\PageBuilder\Livewire\Components\Heading::class,
            'fields' => [
                'title' => ['type' => 'text', 'label' => 'متن عنوان', 'default' => 'عنوان جدید'],
                'align' => ['type' => 'select', 'label' => 'همترازی', 'options' => ['right' => 'راست', 'center' => 'وسط', 'left' => 'چپ'], 'default' => 'right'],
                'color' => ['type' => 'color', 'label' => 'رنگ', 'default' => '#0f3d6e'],
            ],
        ],
        'text' => [
            'label' => 'متن',
            'icon' => 'far fa-paragraph',
            'class' => \Elearnac\PageBuilder\Livewire\Components\Text::class,
            'fields' => [
                'body' => ['type' => 'textarea', 'label' => 'متن', 'default' => 'متن خود را وارد کنید.'],
                'size' => ['type' => 'select', 'label' => 'اندازه', 'options' => ['sm' => 'کوچک', 'md' => 'متوسط', 'lg' => 'بزرگ'], 'default' => 'md'],
            ],
        ],
        'image-slider' => [
            'label' => 'اسلاید تصاویر',
            'icon' => 'far fa-images',
            'class' => \Elearnac\PageBuilder\Livewire\Components\ImageSlider::class,
            'fields' => [
                'images' => ['type' => 'textarea', 'label' => 'آدرس تصاویر (هر خط یک آدرس)', 'default' => ''],
                'style' => ['type' => 'select', 'label' => 'سبک', 'options' => ['default' => 'ساده', 'cards' => 'کارتها', 'fade' => 'محو'], 'default' => 'default'],
                'height' => ['type' => 'number', 'label' => 'ارتفاع (پیکسل)', 'default' => 360],
                'autoplay' => ['type' => 'switch', 'label' => 'پخش خودکار', 'default' => true],
                'breakpoints' => ['type' => 'text', 'label' => 'تعداد اسلایدها موبایل/تبلت/دسکتاپ', 'default' => '1,2,3'],
            ],
        ],
        'course-slider' => [
            'label' => 'اسلاید دوره/محصول',
            'icon' => 'far fa-graduation-cap',
            'class' => \Elearnac\PageBuilder\Livewire\Components\CourseSlider::class,
            'fields' => [
                'category_id' => ['type' => 'model', 'label' => 'دسته بندی', 'default' => ''],
                'limit' => ['type' => 'number', 'label' => 'تعداد', 'default' => 8],
                'show_image' => ['type' => 'switch', 'label' => 'نمایش تصویر', 'default' => true],
                'show_title' => ['type' => 'switch', 'label' => 'نمایش عنوان', 'default' => true],
                'background' => ['type' => 'color', 'label' => 'پسزمینه کارت', 'default' => '#ffffff'],
                'radius' => ['type' => 'number', 'label' => 'گردی گوشهها', 'default' => 16],
                'card_classes' => ['type' => 'text', 'label' => 'کلاسهای اضافی کارت', 'default' => ''],
            ],
        ],
        'reviews' => [
            'label' => 'نظرات',
            'icon' => 'far fa-star',
            'class' => \Elearnac\PageBuilder\Livewire\Components\Reviews::class,
            'fields' => [
                'layout' => ['type' => 'select', 'label' => 'حالت نمایش', 'options' => ['slider' => 'اسلایدر', 'stacked' => 'رویهم', 'grid' => 'شبکه'], 'default' => 'grid'],
                'limit' => ['type' => 'number', 'label' => 'تعداد', 'default' => 6],
                'columns' => ['type' => 'number', 'label' => 'تعداد ستون', 'default' => 3],
            ],
        ],
        'form' => [
            'label' => 'فرم',
            'icon' => 'far fa-square-check',
            'class' => \Elearnac\PageBuilder\Livewire\Components\FormBuilder::class,
            'fields' => [
                'title' => ['type' => 'text', 'label' => 'عنوان فرم', 'default' => 'فرم تماس'],
                'fields' => ['type' => 'textarea', 'label' => 'فیلدها (name|label|type در هر خط)', 'default' => "name|نام|text\nemail|ایمیل|email\nmessage|پیام|textarea"],
                'button' => ['type' => 'text', 'label' => 'متن دکمه', 'default' => 'ارسال'],
            ],
        ],
        'login-gate' => [
            'label' => 'دروازه ورود',
            'icon' => 'far fa-lock',
            'class' => \Elearnac\PageBuilder\Livewire\Components\LoginGate::class,
            'fields' => [
                'message' => ['type' => 'text', 'label' => 'پیام', 'default' => 'برای مشاهده این بخش وارد شوید.'],
                'blur' => ['type' => 'number', 'label' => 'میزان تاری (پیکسل)', 'default' => 10],
            ],
        ],
        'breadcrumb' => [
            'label' => 'مسیر صفحه',
            'icon' => 'far fa-bread-slice',
            'class' => \Elearnac\PageBuilder\Livewire\Components\Breadcrumb::class,
            'fields' => [
                'home' => ['type' => 'text', 'label' => 'عنوان خانه', 'default' => 'خانه'],
            ],
        ],
        'gallery' => [
            'label' => 'گالری تصاویر',
            'icon' => 'far fa-border-all',
            'class' => \Elearnac\PageBuilder\Livewire\Components\Gallery::class,
            'fields' => [
                'images' => ['type' => 'textarea', 'label' => 'آدرس تصاویر (هر خط یک آدرس)', 'default' => ''],
                'columns' => ['type' => 'number', 'label' => 'ستونها', 'default' => 3],
                'radius' => ['type' => 'number', 'label' => 'گردی گوشهها', 'default' => 14],
            ],
        ],
    ],
];
