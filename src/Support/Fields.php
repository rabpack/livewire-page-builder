<?php

namespace Elearnac\PageBuilder\Support;

final class Fields
{
    public static function text(string $label, mixed $default = '', string $section = 'content'): array
    {
        return ['type' => 'text', 'label' => $label, 'default' => $default, 'section' => $section];
    }

    public static function textarea(string $label, mixed $default = '', string $section = 'content'): array
    {
        return ['type' => 'textarea', 'label' => $label, 'default' => $default, 'section' => $section];
    }

    public static function editor(string $label, mixed $default = '', string $section = 'content'): array
    {
        return ['type' => 'editor', 'label' => $label, 'default' => $default, 'section' => $section];
    }

    public static function number(string $label, mixed $default = 0, string $section = 'style', int $min = 0, int $max = 10000): array
    {
        return ['type' => 'number', 'label' => $label, 'default' => $default, 'section' => $section, 'min' => $min, 'max' => $max];
    }

    public static function switch(string $label, mixed $default = false, string $section = 'content'): array
    {
        return ['type' => 'switch', 'label' => $label, 'default' => $default, 'section' => $section];
    }

    public static function select(string $label, array $options, mixed $default = '', string $section = 'content'): array
    {
        return ['type' => 'select', 'label' => $label, 'options' => $options, 'default' => $default, 'section' => $section];
    }

    public static function color(string $label, mixed $default = '#0f172a', string $section = 'style'): array
    {
        return ['type' => 'color', 'label' => $label, 'default' => $default, 'section' => $section];
    }

    public static function font(string $label = 'فونت', mixed $default = 'site', string $section = 'style'): array
    {
        return ['type' => 'font', 'label' => $label, 'default' => $default, 'section' => $section];
    }

    public static function files(string $label, mixed $default = [], string $section = 'content'): array
    {
        return ['type' => 'files', 'label' => $label, 'default' => $default, 'section' => $section];
    }

    public static function model(string $label = 'دسته بندی', mixed $default = '', string $section = 'content'): array
    {
        return ['type' => 'model', 'label' => $label, 'default' => $default, 'section' => $section];
    }

    public static function source(mixed $default = 'static'): array
    {
        return self::select('منبع داده', [
            'static' => 'دستی', 'course' => 'دورهها', 'product' => 'محصولات', 'post' => 'نوشتهها',
            'page' => 'صفحات', 'category' => 'دستهبندیها', 'user' => 'کاربران', 'comment' => 'نظرات',
            'lesson' => 'درسها', 'exam' => 'آزمونها', 'order' => 'سفارشات',
        ], $default);
    }

    public static function repeater(string $label, array $subfields, array $default = [], string $section = 'content'): array
    {
        return ['type' => 'repeater', 'label' => $label, 'subfields' => $subfields, 'default' => $default, 'section' => $section];
    }

    public static function link(string $label = 'لینک', mixed $default = '#', string $section = 'content'): array
    {
        return self::text($label, $default, $section);
    }

    public static function icon(string $label = 'آیکون', mixed $default = 'far fa-star', string $section = 'content'): array
    {
        return self::text($label, $default, $section);
    }

    public static function alignment(mixed $default = 'right'): array
    {
        return self::select('همترازی', ['right' => 'راست', 'center' => 'وسط', 'left' => 'چپ'], $default, 'style');
    }

    public static function typography(mixed $size = 16, mixed $color = '#334155', bool $withFont = true): array
    {
        $fields = [];
        if ($withFont) {
            $fields['font'] = self::font();
        }
        $fields['size'] = self::number('اندازه فونت (پیکسل)', $size, 'style', 8, 200);
        $fields['weight'] = self::select('ضخامت فونت', ['300' => 'سبک', '400' => 'عادی', '600' => 'نیمه ضخیم', '700' => 'ضخیم', '800' => 'خیلی ضخیم'], '400', 'style');
        $fields['line_height'] = self::number('فاصله خطوط', 1.6, 'style', 0.5, 5);
        $fields['letter_spacing'] = self::number('فاصله حروف (پیکسل)', 0, 'style', -10, 40);
        $fields['color'] = self::color('رنگ متن', $color);
        $fields['color_dark'] = self::color('رنگ متن در حالت تاریک', '#e2e8f0');
        $fields['align'] = self::alignment();
        return $fields;
    }

    public static function background(mixed $default = '#ffffff', mixed $dark = '#0f172a'): array
    {
        return [
            'background' => self::color('پسزمینه', $default),
            'background_dark' => self::color('پسزمینه در حالت تاریک', $dark),
        ];
    }

    public static function border(mixed $radius = 12): array
    {
        return [
            'radius' => self::number('گردی گوشهها', $radius),
            'border_color' => self::color('رنگ کادر', '#e2e8f0'),
            'border_width' => self::number('ضخامت کادر', 1),
            'shadow' => self::switch('سایه', false, 'style'),
        ];
    }

    public static function spacing(int $padding = 16, int $gap = 16): array
    {
        return [
            'padding' => self::number('پدینگ (پیکسل)', $padding),
            'gap' => self::number('فاصله (پیکسل)', $gap),
        ];
    }

    public static function slider(): array
    {
        return [
            'slides_desktop' => self::number('اسلاید دسکتاپ', 3),
            'slides_tablet' => self::number('اسلاید تبلت', 2),
            'slides_mobile' => self::number('اسلاید موبایل', 1),
            'autoplay' => self::switch('پخش خودکار', true, 'advanced'),
            'loop' => self::switch('چرخش مکرر', true, 'advanced'),
            'arrows' => self::switch('فلشها', true, 'advanced'),
            'pagination' => self::switch('نقاط', true, 'advanced'),
            'speed' => self::number('سرعت (میلیثانیه)', 700, 'advanced', 100, 5000),
        ];
    }

    public static function merge(array ...$groups): array
    {
        return array_merge(...$groups);
    }
}
