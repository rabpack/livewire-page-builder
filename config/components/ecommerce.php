<?php

use Elearnac\PageBuilder\Support\ComponentBlueprint;
use Elearnac\PageBuilder\Support\Fields;

$card = Fields::merge(
    Fields::background(),
    Fields::border(14),
    Fields::spacing(16, 12),
    Fields::typography(15, '#334155')
);

$listing = Fields::merge($card, [
    'source' => Fields::source('product'),
    'category_id' => Fields::model(),
    'limit' => Fields::number('تعداد', 8, 'content', 1, 100),
    'columns' => Fields::number('ستونها', 4),
    'show_image' => Fields::switch('نمایش تصویر', true),
    'show_price' => Fields::switch('نمایش قیمت', true),
    'show_rating' => Fields::switch('نمایش امتیاز', true),
    'show_cart' => Fields::switch('نمایش دکمه خرید', true),
    'badge' => Fields::text('برچسب ویژه', ''),
]);

return [
    'product-grid' => ComponentBlueprint::make('شبکه محصولات', 'فروشگاه', 'far fa-boxes', 'commerce', $listing, ['variant' => 'products', 'source' => 'product']),
    'product-carousel' => ComponentBlueprint::make('اسلاید محصولات', 'فروشگاه', 'far fa-shopping-bag', 'commerce', Fields::merge($listing, Fields::slider()), ['variant' => 'products', 'source' => 'product']),
    'product-card' => ComponentBlueprint::make('کارت محصول', 'فروشگاه', 'far fa-box', 'commerce', Fields::merge($card, ['source' => Fields::source('product'), 'limit' => Fields::number('تعداد', 1, 'content', 1, 10), 'show_price' => Fields::switch('نمایش قیمت', true), 'show_cart' => Fields::switch('نمایش دکمه خرید', true)]), ['variant' => 'product', 'source' => 'product']),
    'product-categories' => ComponentBlueprint::make('دستهبندی محصولات', 'فروشگاه', 'far fa-folder-tree', 'dynamic', Fields::merge($card, ['source' => Fields::source('category'), 'limit' => Fields::number('تعداد', 8), 'columns' => Fields::number('ستونها', 4), 'show_image' => Fields::switch('نمایش تصویر', true)]), ['variant' => 'category', 'source' => 'category']),
    'add-to-cart' => ComponentBlueprint::make('افزودن به سبد', 'فروشگاه', 'far fa-cart-plus', 'commerce', Fields::merge(Fields::typography(14, '#ffffff'), Fields::background('#18b7a5', '#0f3d6e'), ['label' => Fields::text('متن دکمه', 'افزودن به سبد'), 'product_id' => Fields::text('شناسه محصول', ''), 'quantity' => Fields::number('تعداد پیشفرض', 1), 'show_quantity' => Fields::switch('نمایش تعداد', true)]), ['variant' => 'add-to-cart']),
    'cart' => ComponentBlueprint::make('سبد خرید', 'فروشگاه', 'far fa-shopping-cart', 'commerce', Fields::merge($card, ['title' => Fields::text('عنوان', 'سبد خرید'), 'show_quantity' => Fields::switch('نمایش تعداد', true), 'show_remove' => Fields::switch('نمایش حذف', true), 'checkout_link' => Fields::link('لینک پرداخت', '/checkout')]), ['variant' => 'cart']),
    'mini-cart' => ComponentBlueprint::make('سبد خرید کوچک', 'فروشگاه', 'far fa-cart-arrow-down', 'commerce', Fields::merge($card, ['label' => Fields::text('عنوان دکمه', 'سبد خرید'), 'show_count' => Fields::switch('نمایش تعداد', true)]), ['variant' => 'mini-cart']),
    'checkout-summary' => ComponentBlueprint::make('خلاصه پرداخت', 'فروشگاه', 'far fa-receipt', 'commerce', Fields::merge($card, ['title' => Fields::text('عنوان', 'خلاصه سفارش'), 'show_tax' => Fields::switch('نمایش مالیات', true), 'show_shipping' => Fields::switch('نمایش حمل و نقل', true), 'button' => Fields::text('متن دکمه', 'پرداخت')]), ['variant' => 'checkout']),
    'wishlist' => ComponentBlueprint::make('لیست علاقهمندیها', 'فروشگاه', 'far fa-heart', 'commerce', Fields::merge($card, ['source' => Fields::source('product'), 'limit' => Fields::number('تعداد', 8), 'show_remove' => Fields::switch('نمایش حذف', true)]), ['variant' => 'wishlist', 'source' => 'product']),
    'product-reviews' => ComponentBlueprint::make('نظرات محصول', 'فروشگاه', 'far fa-star', 'commerce', Fields::merge($card, ['source' => Fields::source('comment'), 'limit' => Fields::number('تعداد', 6), 'show_rating' => Fields::switch('نمایش امتیاز', true), 'show_form' => Fields::switch('نمایش فرم نظر', false)]), ['variant' => 'reviews', 'source' => 'comment']),
    'reviews-summary' => ComponentBlueprint::make('خلاصه نظرات', 'فروشگاه', 'far fa-chart-pie', 'commerce', Fields::merge($card, ['rating' => Fields::number('میانگین امتیاز', 4, 'content', 0, 5), 'count' => Fields::number('تعداد نظرات', 120), 'show_bars' => Fields::switch('نمایش نمودار امتیازها', true)]), ['variant' => 'reviews-summary']),
    'price' => ComponentBlueprint::make('قیمت', 'فروشگاه', 'far fa-tag', 'commerce', Fields::merge(Fields::typography(22, '#0f172a'), ['amount' => Fields::text('قیمت', '990,000 تومان'), 'old_amount' => Fields::text('قیمت قبلی', ''), 'currency' => Fields::text('واحد پول', 'تومان'), 'show_discount' => Fields::switch('نمایش تخفیف', true)]), ['variant' => 'price']),
    'sale-badge' => ComponentBlueprint::make('برچسب تخفیف', 'فروشگاه', 'far fa-percent', 'icon', Fields::merge(Fields::typography(13, '#ffffff'), ['text' => Fields::text('متن برچسب', 'تخفیف ویژه'), 'background' => Fields::color('پسزمینه', '#e11d48'), 'background_dark' => Fields::color('پسزمینه تاریک', '#be123c')]), ['variant' => 'badge']),
    'stock' => ComponentBlueprint::make('موجودی', 'فروشگاه', 'far fa-check-circle', 'commerce', Fields::merge(Fields::typography(13, '#16a34a'), ['status' => Fields::select('وضعیت', ['in' => 'موجود', 'out' => 'ناموجود', 'low' => 'کم'], 'in'), 'label' => Fields::text('متن سفارشی', '')]), ['variant' => 'stock']),
    'payment-button' => ComponentBlueprint::make('پرداخت', 'فروشگاه', 'far fa-credit-card', 'commerce', Fields::merge(Fields::typography(14, '#ffffff'), Fields::background('#18b7a5', '#0f3d6e'), ['label' => Fields::text('متن دکمه', 'پرداخت'), 'amount' => Fields::text('مبلغ', ''), 'gateway' => Fields::select('درگاه', ['auto' => 'خودکار', 'zarinpal' => 'زرینپال'], 'auto')]), ['variant' => 'payment']),
];
