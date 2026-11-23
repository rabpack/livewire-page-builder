<?php

namespace Elearnac\PageBuilder\Services;

final class FieldPresentation
{
    private const ICONS = [
        'text' => 'far fa-pen-line',
        'link' => 'far fa-link',
        'icon' => 'far fa-star',
        'textarea' => 'far fa-align-right',
        'editor' => 'far fa-newspaper',
        'number' => 'far fa-sort-numeric-up',
        'switch' => 'far fa-toggle-on',
        'select' => 'far fa-list-alt',
        'model' => 'far fa-th-list',
        'font' => 'far fa-font',
        'color' => 'far fa-palette',
        'files' => 'far fa-images',
        'repeater' => 'far fa-layer-group',
    ];

    private const HINTS = [
        'title' => 'این عنوان در پیشنمایش و صفحه نهایی نمایش داده میشود.',
        'link' => 'آدرس کامل یا مسیر داخلی صفحه را وارد کنید.',
        'image' => 'تصویر از مدارک سایت انتخاب میشود.',
        'images' => 'برای انتخاب چند تصویر، آنها را یکی پس از دیگری انتخاب کنید.',
        'font' => 'فونت انتخابشده فقط روی این المان اعمال میشود.',
        'color' => 'رنگ را میتوانید با کد هگزادسیمال هم وارد کنید.',
        'radius' => 'گردی گوشهها بر حسب پیکسل است.',
        'padding' => 'فاصله داخلی المان بر حسب پیکسل است.',
        'gap' => 'فاصله بین موارد بر حسب پیکسل است.',
        'autoplay' => 'پخش خودکار هنگام نمایش صفحه آغاز میشود.',
        'loop' => 'پس از رسیدن به آخرین مورد، دوباره از ابتدا نمایش داده میشود.',
        'source' => 'منبع دادهها را مشخص کنید.',
        'alt' => 'متن جایگزین برای موتورهای جستجو و کاربران دارای مشکلات بینایی.',
    ];

    private const TYPE_HINTS = [
        'files' => 'از مدارک سایت انتخاب کنید؛ تصویر بلافاصله در پیشنمایش دیده میشود.',
        'repeater' => 'هر مورد را با دکمه افزودن ایجاد و سپس تنظیمات آن را پر کنید.',
        'editor' => 'متن غنی وارد کنید؛ محتوا قبل از نمایش پاکسازی میشود.',
        'color' => 'رنگ دلخواه خود را انتخاب کنید.',
        'number' => 'با نوار لغزنده یا فیلد عددی تنظیم کنید.',
        'switch' => 'برای روشن یا خاموش کردن کلیک کنید.',
    ];

    public function icon(string $type): string
    {
        return self::ICONS[$type] ?? 'far fa-sliders-h';
    }

    public function hint(string $key, string $type): string
    {
        return self::HINTS[$key] ?? self::TYPE_HINTS[$type] ?? '';
    }
}
