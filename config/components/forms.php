<?php

use Elearnac\PageBuilder\Support\ComponentBlueprint;
use Elearnac\PageBuilder\Support\Fields;

$form = Fields::merge(
    Fields::background(),
    Fields::border(14),
    Fields::spacing(20, 12),
    Fields::typography(14, '#334155'),
    [
        'fields' => Fields::repeater('فیلدهای فرم', ['name' => 'text', 'label' => 'text', 'type' => 'select', 'required' => 'switch', 'options' => 'text'], [
            ['name' => 'name', 'label' => 'نام', 'type' => 'text', 'required' => true, 'options' => ''],
            ['name' => 'email', 'label' => 'ایمیل', 'type' => 'email', 'required' => true, 'options' => ''],
        ]),
        'button' => Fields::text('متن دکمه', 'ارسال'),
        'success' => Fields::text('پیام موفقیت', 'پیام شما با موفقیت ثبت شد.'),
        'email_to' => Fields::text('ایمیل دریافتکننده', '', 'advanced'),
        'redirect' => Fields::text('لینک پس از ارسال', '', 'advanced'),
    ]
);

$auth = Fields::merge(
    Fields::background(),
    Fields::border(14),
    Fields::spacing(22, 12),
    Fields::typography(14, '#334155'),
    [
        'title' => Fields::text('عنوان', 'ورود به سایت'),
        'description' => Fields::textarea('توضیحات', ''),
        'button' => Fields::text('متن دکمه', 'ورود'),
        'redirect' => Fields::text('لینک پس از موفقیت', '/profile', 'advanced'),
    ]
);

return [
    'form' => ComponentBlueprint::make('فرم ساز', 'فرم', 'far fa-square-check', 'forms', $form, ['variant' => 'form']),
    'contact-form' => ComponentBlueprint::make('فرم تماس', 'فرم', 'far fa-envelope', 'forms', array_replace_recursive($form, ['title' => Fields::text('عنوان فرم', 'تماس با ما'), 'description' => Fields::textarea('توضیحات', 'پیام خود را ارسال کنید.')]), ['variant' => 'form']),
    'newsletter' => ComponentBlueprint::make('خبرنامه', 'فرم', 'far fa-newspaper', 'forms', Fields::merge(Fields::background(), Fields::border(14), Fields::typography(14, '#334155'), ['title' => Fields::text('عنوان', 'عضویت در خبرنامه'), 'description' => Fields::textarea('توضیحات', ''), 'placeholder' => Fields::text('متن راهنما', 'آدرس ایمیل'), 'button' => Fields::text('متن دکمه', 'عضویت')]), ['variant' => 'newsletter']),
    'search-form' => ComponentBlueprint::make('فرم جستجو', 'فرم', 'far fa-search', 'forms', Fields::merge(Fields::background(), Fields::border(10), Fields::typography(14, '#334155'), ['placeholder' => Fields::text('متن راهنما', 'جستجوی دوره، نوشته یا محصول'), 'button' => Fields::text('متن دکمه', 'جستجو'), 'action' => Fields::text('لینک جستجو', '/search'), 'show_filters' => Fields::switch('نمایش فیلترها', false)]), ['variant' => 'search']),
    'login' => ComponentBlueprint::make('فرم ورود', 'فرم', 'far fa-sign-in', 'forms', $auth, ['variant' => 'login']),
    'register' => ComponentBlueprint::make('فرم ثبت نام', 'فرم', 'far fa-user-plus', 'forms', array_replace_recursive($auth, ['title' => Fields::text('عنوان', 'ایجاد حساب کاربری'), 'button' => Fields::text('متن دکمه', 'ثبت نام')]), ['variant' => 'register']),
    'lost-password' => ComponentBlueprint::make('فراموشی رمز', 'فرم', 'far fa-key', 'forms', array_replace_recursive($auth, ['title' => Fields::text('عنوان', 'بازیابی رمز عبور'), 'button' => Fields::text('متن دکمه', 'ارسال کد')]), ['variant' => 'lost-password']),
    'reset-password' => ComponentBlueprint::make('تغییر رمز', 'فرم', 'far fa-lock-open', 'forms', array_replace_recursive($auth, ['title' => Fields::text('عنوان', 'تعیین رمز جدید'), 'button' => Fields::text('متن دکمه', 'ذخیره رمز')]), ['variant' => 'reset-password']),
    'profile-edit' => ComponentBlueprint::make('ویرایش پروفایل', 'فرم', 'far fa-user-edit', 'forms', Fields::merge($auth, ['show_avatar' => Fields::switch('نمایش آواتار', true)]), ['variant' => 'profile']),
    'booking-form' => ComponentBlueprint::make('فرم رزرو', 'فرم', 'far fa-calendar-check', 'forms', Fields::merge($form, ['title' => Fields::text('عنوان فرم', 'رزرو جلسه'), 'date_label' => Fields::text('عنوان تاریخ', 'تاریخ مورد نظر')]), ['variant' => 'booking']),
    'survey' => ComponentBlueprint::make('نظرسنجی', 'فرم', 'far fa-poll', 'forms', Fields::merge($form, ['title' => Fields::text('عنوان فرم', 'نظرسنجی'), 'show_progress' => Fields::switch('نمایش پیشرفت', true)]), ['variant' => 'survey']),
    'payment-link' => ComponentBlueprint::make('دکمه پرداخت', 'فرم', 'far fa-credit-card', 'button', Fields::merge(Fields::typography(14, '#ffffff'), Fields::background('#0f3d6e', '#18b7a5'), ['label' => Fields::text('متن دکمه', 'پرداخت'), 'amount' => Fields::text('مبلغ', ''), 'gateway' => Fields::select('درگاه', ['auto' => 'خودکار', 'zarinpal' => 'زرینپال', 'novin' => 'نوین'], 'auto')]), ['variant' => 'payment']),
    'mailchimp' => ComponentBlueprint::make('Mailchimp', 'فرم', 'far fa-envelope-open', 'forms', Fields::merge(Fields::background(), Fields::border(12), ['title' => Fields::text('عنوان', 'عضویت در خبرنامه'), 'list_id' => Fields::text('شناسه لیست', '')]), ['variant' => 'newsletter']),
    'hook-form' => ComponentBlueprint::make('فرم هوک', 'فرم', 'far fa-plug', 'forms', Fields::merge(Fields::background(), Fields::border(12), ['hook' => Fields::text('نام هوک', 'contact'), 'body' => Fields::textarea('جایگزین', 'فرمی برای نمایش وجود ندارد.')]), ['variant' => 'hook']),
    'ticket-form' => ComponentBlueprint::make('فرم تیکت', 'فرم', 'far fa-ticket-alt', 'forms', Fields::merge($form, ['title' => Fields::text('عنوان فرم', 'ارسال تیکت'), 'department' => Fields::select('بخش', ['support' => 'پشتیبانی', 'sales' => 'فروش', 'technical' => 'فنی'], 'support')]), ['variant' => 'ticket']),
];
