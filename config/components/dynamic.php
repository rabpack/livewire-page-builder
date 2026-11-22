<?php

use Elearnac\PageBuilder\Support\ComponentBlueprint;
use Elearnac\PageBuilder\Support\Fields;

$listing = Fields::merge(
    Fields::background(),
    Fields::border(14),
    Fields::spacing(16, 14),
    Fields::typography(15, '#334155'),
    [
        'source' => Fields::source('course'),
        'category_id' => Fields::model(),
        'limit' => Fields::number('تعداد', 8, 'content', 1, 100),
        'columns' => Fields::number('ستونها', 3),
        'show_image' => Fields::switch('نمایش تصویر', true),
        'show_title' => Fields::switch('نمایش عنوان', true),
        'show_text' => Fields::switch('نمایش توضیحات', true),
        'show_meta' => Fields::switch('نمایش فراداده', true),
        'show_link' => Fields::switch('نمایش لینک', true),
        'order' => Fields::select('ترتیب', ['latest' => 'جدیدترین', 'oldest' => 'قدیمیترین', 'title' => 'بر اساس عنوان'], 'latest', 'advanced'),
    ]
);

$single = Fields::merge(
    Fields::background(),
    Fields::border(14),
    Fields::spacing(18, 10),
    Fields::typography(15, '#334155'),
    [
        'source' => Fields::source('post'),
        'limit' => Fields::number('تعداد', 1, 'content', 1, 10),
        'show_image' => Fields::switch('نمایش تصویر', true),
        'show_title' => Fields::switch('نمایش عنوان', true),
        'show_text' => Fields::switch('نمایش متن', true),
    ]
);

return [
    'course-grid' => ComponentBlueprint::make('شبکه دورهها', 'دادهها', 'far fa-graduation-cap', 'dynamic', $listing, ['variant' => 'course', 'source' => 'course']),
    'course-carousel' => ComponentBlueprint::make('اسلاید دورهها', 'دادهها', 'far fa-graduation-cap', 'dynamic', Fields::merge($listing, Fields::slider()), ['variant' => 'course', 'source' => 'course']),
    'course-card' => ComponentBlueprint::make('کارت دوره', 'دادهها', 'far fa-award', 'dynamic', $single, ['variant' => 'course', 'source' => 'course']),
    'lesson-list' => ComponentBlueprint::make('لیست درسها', 'دادهها', 'far fa-play-circle', 'user', Fields::merge($listing, ['show_progress' => Fields::switch('نمایش پیشرفت', true), 'show_duration' => Fields::switch('نمایش مدت زمان', true)]), ['variant' => 'lessons', 'source' => 'lesson']),
    'exam-card' => ComponentBlueprint::make('کارت آزمون', 'دادهها', 'far fa-question-circle', 'user', Fields::merge($single, ['show_deadline' => Fields::switch('نمایش مهلت', true), 'show_attempts' => Fields::switch('نمایش تلاشها', true)]), ['variant' => 'exam', 'source' => 'exam']),
    'quiz-result' => ComponentBlueprint::make('نتیجه آزمون', 'دادهها', 'far fa-chart-pie', 'alert', Fields::merge(Fields::background(), Fields::border(14), ['score' => Fields::number('نمره', 0), 'total' => Fields::number('حداکثر نمره', 10, 'content', 1, 1000), 'passing' => Fields::number('حد قبولی (درصد)', 60, 'content', 0, 100), 'message' => Fields::text('پیام', '')]), ['variant' => 'result']),
    'student-progress' => ComponentBlueprint::make('پیشرفت دانشجو', 'دادهها', 'far fa-chart-line', 'user', Fields::merge($single, ['course_id' => Fields::model('دوره', ''), 'show_chart' => Fields::switch('نمایش نمودار', true)]), ['variant' => 'progress', 'source' => 'course']),
    'certificate' => ComponentBlueprint::make('گواهینامه', 'دادهها', 'far fa-certificate', 'user', Fields::merge($single, ['name' => Fields::text('نام گیرنده', ''), 'course' => Fields::text('نام دوره', ''), 'date' => Fields::text('تاریخ صدور', ''), 'code' => Fields::text('کد گواهی', '')]), ['variant' => 'certificate']),
    'teacher-card' => ComponentBlueprint::make('کارت مدرس', 'دادهها', 'far fa-chalkboard-teacher', 'user', Fields::merge($single, ['source' => Fields::source('user'), 'role' => Fields::text('سمت', 'مدرس'), 'social' => Fields::switch('نمایش شبکهها', true)]), ['variant' => 'teacher', 'source' => 'user']),
    'site-logo' => ComponentBlueprint::make('لوگوی سایت', 'دادهها', 'far fa-image', 'heading', Fields::merge(Fields::typography(20, '#0f172a'), ['image' => Fields::files('لوگوی سفارشی', []), 'title' => Fields::text('متن جایگزین', 'لوگوی سایت'), 'link' => Fields::link('لینک', '/'), 'width' => Fields::number('عرض', 140)]), ['variant' => 'logo']),
    'site-navigation' => ComponentBlueprint::make('منوی سایت', 'دادهها', 'far fa-bars', 'navigation', Fields::merge(Fields::background(), Fields::border(8), Fields::typography(14, '#334155'), ['items' => Fields::repeater('لینکها', ['title' => 'text', 'url' => 'text', 'icon' => 'text'], [['title' => 'خانه', 'url' => '/', 'icon' => '']]), 'orientation' => Fields::select('جهت', ['horizontal' => 'افقی', 'vertical' => 'عمودی'], 'horizontal'), 'sticky' => Fields::switch('چسبنده', false, 'advanced')]), ['variant' => 'nav']),
    'breadcrumb' => ComponentBlueprint::make('مسیر صفحه', 'دادهها', 'far fa-bread-slice', 'navigation', Fields::merge(Fields::typography(13, '#64748b'), ['home' => Fields::text('عنوان خانه', 'خانه'), 'separator' => Fields::text('جداکننده', '/'), 'show_current' => Fields::switch('نمایش صفحه فعلی', true)]), ['variant' => 'breadcrumb']),
    'page-title' => ComponentBlueprint::make('عنوان صفحه', 'دادهها', 'far fa-heading', 'heading', Fields::merge(Fields::typography(30, '#0f172a'), ['title' => Fields::text('عنوان سفارشی', ''), 'show_description' => Fields::switch('نمایش توضیحات', true)]), ['variant' => 'page-title']),
    'post-title' => ComponentBlueprint::make('عنوان نوشته', 'دادهها', 'far fa-heading', 'heading', Fields::merge(Fields::typography(28, '#0f172a'), ['level' => Fields::select('سطح عنوان', ['h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3'], 'h1')]), ['variant' => 'post-title']),
    'post-excerpt' => ComponentBlueprint::make('خلاصه نوشته', 'دادهها', 'far fa-align-left', 'text', Fields::merge(Fields::typography(15, '#475569'), ['limit' => Fields::number('تعداد کاراکتر', 220, 'content', 20, 2000)]), ['variant' => 'excerpt']),
    'post-content' => ComponentBlueprint::make('محتوای نوشته', 'دادهها', 'far fa-file-alt', 'text', Fields::typography(16, '#334155'), ['variant' => 'post-content']),
    'post-featured-image' => ComponentBlueprint::make('تصویر شاخص نوشته', 'دادهها', 'far fa-image', 'icon', Fields::merge(Fields::border(12), ['height' => Fields::number('ارتفاع', 320), 'fit' => Fields::select('نحوه نمایش', ['cover' => 'پر کردن', 'contain' => 'کامل'], 'cover')]), ['variant' => 'featured-image']),
    'post-meta' => ComponentBlueprint::make('فراداده نوشته', 'دادهها', 'far fa-info', 'list', Fields::merge(Fields::typography(13, '#64748b'), ['show_author' => Fields::switch('نمایش نویسنده', true), 'show_date' => Fields::switch('نمایش تاریخ', true), 'show_comments' => Fields::switch('نمایش نظرات', true)]), ['variant' => 'post-meta']),
    'post-categories' => ComponentBlueprint::make('دستهبندیهای نوشته', 'دادهها', 'far fa-folder', 'list', Fields::merge(Fields::typography(13, '#64748b'), ['separator' => Fields::text('جداکننده', '، ')]), ['variant' => 'post-categories']),
    'post-tags' => ComponentBlueprint::make('برچسبهای نوشته', 'دادهها', 'far fa-tags', 'list', Fields::merge(Fields::typography(13, '#64748b'), ['separator' => Fields::text('جداکننده', ' ')]), ['variant' => 'post-tags']),
    'post-comments' => ComponentBlueprint::make('نظرات نوشته', 'دادهها', 'far fa-comments', 'dynamic', Fields::merge($listing, ['source' => Fields::source('comment'), 'show_form' => Fields::switch('نمایش فرم ارسال نظر', true)]), ['variant' => 'comments', 'source' => 'comment']),
    'post-navigation' => ComponentBlueprint::make('ناوبری نوشته', 'دادهها', 'far fa-arrows-alt-h', 'navigation', Fields::merge(Fields::background(), Fields::border(12), Fields::typography(14, '#334155'), ['previous_label' => Fields::text('برچسب قبلی', 'نوشته قبلی'), 'next_label' => Fields::text('برچسب بعدی', 'نوشته بعدی')]), ['variant' => 'post-navigation']),
    'archive-posts' => ComponentBlueprint::make('آرشیو نوشتهها', 'دادهها', 'far fa-archive', 'dynamic', Fields::merge($listing, ['group_by' => Fields::select('گروهبندی', ['none' => 'بدون گروهبندی', 'month' => 'ماهانه', 'category' => 'دستهبندی'], 'none')]), ['variant' => 'archive', 'source' => 'post']),
    'loop-grid' => ComponentBlueprint::make('شبکه حلقهای', 'دادهها', 'far fa-layer-group', 'dynamic', Fields::merge($listing, ['template' => Fields::text('الگوی مورد استفاده', 'card')]), ['variant' => 'loop']),
    'template' => ComponentBlueprint::make('الگوی ذخیرهشده', 'دادهها', 'far fa-object-group', 'system', ['template' => Fields::text('شناسه الگو', ''), 'fallback' => Fields::textarea('جایگزین', 'الگویی یافت نشد.')], ['variant' => 'template']),
    'search-results' => ComponentBlueprint::make('نتایج جستجو', 'دادهها', 'far fa-search', 'dynamic', Fields::merge($listing, ['query' => Fields::text('عبارت جستجو (پیشفرض)', ''), 'source' => Fields::source('course')]), ['variant' => 'search']),
    'recent-posts' => ComponentBlueprint::make('آخرین نوشتهها', 'دادهها', 'far fa-clock', 'dynamic', $listing, ['variant' => 'post', 'source' => 'post']),
    'popular-posts' => ComponentBlueprint::make('محبوبترین نوشتهها', 'دادهها', 'far fa-fire', 'dynamic', $listing, ['variant' => 'post', 'source' => 'post']),
    'related-posts' => ComponentBlueprint::make('نوشتههای مرتبط', 'دادهها', 'far fa-link', 'dynamic', $listing, ['variant' => 'post', 'source' => 'post']),
];
