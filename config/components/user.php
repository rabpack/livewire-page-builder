<?php

use Elearnac\PageBuilder\Support\ComponentBlueprint;
use Elearnac\PageBuilder\Support\Fields;

$card = Fields::merge(
    Fields::background(),
    Fields::border(14),
    Fields::spacing(18, 12),
    Fields::typography(15, '#334155'),
    ['empty' => Fields::text('پیام خالی', 'موردی برای نمایش وجود ندارد.', 'advanced')]
);

return [
    'profile-header' => ComponentBlueprint::make('سربرگ پروفایل', 'کاربر', 'far fa-user-circle', 'user', Fields::merge($card, ['name' => Fields::text('نام پیشفرض', 'کاربر'), 'bio' => Fields::textarea('بیوگرافی', ''), 'image' => Fields::files('آواتار', []), 'show_stats' => Fields::switch('نمایش آمار', true)]), ['variant' => 'profile']),
    'my-courses' => ComponentBlueprint::make('دورههای من', 'کاربر', 'far fa-graduation-cap', 'user', Fields::merge($card, ['source' => Fields::source('course'), 'limit' => Fields::number('تعداد', 6), 'columns' => Fields::number('ستونها', 3), 'show_progress' => Fields::switch('نمایش پیشرفت', true), 'show_key' => Fields::switch('نمایش کلید پخشکننده', false)]), ['variant' => 'my-courses', 'source' => 'course']),
    'notifications' => ComponentBlueprint::make('اعلانها', 'کاربر', 'far fa-bell', 'user', Fields::merge($card, ['limit' => Fields::number('تعداد', 8), 'show_time' => Fields::switch('نمایش زمان', true), 'only_unread' => Fields::switch('فقط خواندهنشدهها', false)]), ['variant' => 'notifications']),
    'wallet' => ComponentBlueprint::make('کیف پول', 'کاربر', 'far fa-wallet', 'user', Fields::merge($card, ['title' => Fields::text('عنوان', 'کیف پول من'), 'show_charge' => Fields::switch('نمایش دکمه شارژ', true), 'show_transactions' => Fields::switch('نمایش تراکنشها', true)]), ['variant' => 'wallet']),
    'dashboard-stats' => ComponentBlueprint::make('آمار کاربر', 'کاربر', 'far fa-chart-bar', 'counter', Fields::merge($card, ['items' => Fields::repeater('آمارها', ['label' => 'text', 'value' => 'text', 'icon' => 'text'], [['label' => 'دورهها', 'value' => '12', 'icon' => 'far fa-graduation-cap'], ['label' => 'آزمونها', 'value' => '8', 'icon' => 'far fa-question-circle']]), 'columns' => Fields::number('ستونها', 4)]), ['variant' => 'stats']),
    'subscription' => ComponentBlueprint::make('اشتراکها', 'کاربر', 'far fa-sync', 'user', Fields::merge($card, ['limit' => Fields::number('تعداد', 5), 'show_status' => Fields::switch('نمایش وضعیت', true)]), ['variant' => 'subscription']),
    'favorites' => ComponentBlueprint::make('علاقهمندیها', 'کاربر', 'far fa-heart', 'user', Fields::merge($card, ['source' => Fields::source('course'), 'limit' => Fields::number('تعداد', 8), 'columns' => Fields::number('ستونها', 3)]), ['variant' => 'favorites']),
    'activity' => ComponentBlueprint::make('فعالیتها', 'کاربر', 'far fa-history', 'list', Fields::merge($card, ['items' => Fields::repeater('فعالیتها', ['title' => 'text', 'body' => 'text', 'icon' => 'text'], []), 'limit' => Fields::number('تعداد', 8)]), ['variant' => 'activity']),
    'message-list' => ComponentBlueprint::make('پیامها', 'کاربر', 'far fa-envelope', 'user', Fields::merge($card, ['limit' => Fields::number('تعداد', 8), 'show_unread' => Fields::switch('نمایش علامت خواندهنشده', true)]), ['variant' => 'messages']),
    'booking-list' => ComponentBlueprint::make('رزروها', 'کاربر', 'far fa-calendar', 'user', Fields::merge($card, ['limit' => Fields::number('تعداد', 8), 'show_status' => Fields::switch('نمایش وضعیت', true)]), ['variant' => 'bookings']),
];
