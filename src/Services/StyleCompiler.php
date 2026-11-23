<?php

namespace Elearnac\PageBuilder\Services;

final class StyleCompiler
{
    private const COLOR_PROPERTIES = [
        'color' => '--pb-color',
        'color_dark' => '--pb-color-dark',
        'background' => '--pb-bg',
        'background_dark' => '--pb-bg-dark',
        'accent' => '--pb-accent',
        'accent_dark' => '--pb-accent-dark',
        'border_color' => '--pb-border',
    ];

    private const LENGTH_PROPERTIES = [
        'radius' => '--pb-radius',
        'padding' => '--pb-padding',
        'gap' => '--pb-gap',
        'size' => '--pb-size',
        'height' => '--pb-height',
        'width' => '--pb-width',
        'font_size' => '--pb-font-size',
        'line_height' => '--pb-line-height',
        'letter_spacing' => '--pb-letter-spacing',
        'border_width' => '--pb-border-width',
    ];

    public function __construct(private FontRegistry $fonts, private ResponsiveSettings $responsive)
    {
    }

    public function variables(array $settings, string $device = 'desktop'): string
    {
        $settings = $this->responsive->resolve($settings, $device);
        $declarations = ['--pb-font:'.$this->quoteFont($this->fonts->stack($settings['font'] ?? null))];

        foreach (self::COLOR_PROPERTIES as $key => $variable) {
            if (isset($settings[$key])) {
                $color = $this->color((string) $settings[$key]);
                if ($color !== null) {
                    $declarations[] = $variable.':'.$color;
                }
            }
        }

        foreach (self::LENGTH_PROPERTIES as $key => $variable) {
            if (isset($settings[$key]) && is_numeric($settings[$key])) {
                $declarations[] = $variable.':'.$this->length((float) $settings[$key], $key);
            }
        }

        if (isset($settings['weight'])) {
            $declarations[] = '--pb-weight:'.$this->integer((string) $settings['weight'], 100, 950);
        }
        if (isset($settings['align']) && in_array($settings['align'], ['right', 'center', 'left'], true)) {
            $declarations[] = '--pb-align:'.$settings['align'];
        }

        return implode(';', $declarations);
    }

    public function pageVariables(array $settings, string $device = 'desktop'): string
    {
        $settings = $this->responsive->resolve($settings, $device);
        $declarations = ['--pb-font:'.$this->quoteFont($this->fonts->stack($settings['font'] ?? null))];

        foreach ([
            'background' => '--pb-bg',
            'background_dark' => '--pb-bg-dark',
            'text' => '--pb-color',
            'text_dark' => '--pb-color-dark',
            'accent' => '--pb-accent',
            'accent_dark' => '--pb-accent-dark',
        ] as $key => $variable) {
            if (isset($settings[$key])) {
                $color = $this->color((string) $settings[$key]);
                if ($color !== null) {
                    $declarations[] = $variable.':'.$color;
                }
            }
        }

        if (isset($settings['container_width']) && is_numeric($settings['container_width'])) {
            $declarations[] = '--pb-container:'.$this->length((float) $settings['container_width'], 'width');
        }
        if (isset($settings['radius']) && is_numeric($settings['radius'])) {
            $declarations[] = '--pb-radius:'.$this->length((float) $settings['radius'], 'radius');
        }

        return implode(';', $declarations);
    }

    public function pageResponsiveCss(array $settings): string
    {
        $css = '.pb-page{'.$this->important($this->pageVariables($settings, 'desktop')).'}';
        $queries = [
            'wide' => '@media(min-width:1600px)',
            'tablet' => '@media(max-width:1024px)',
            'mobile' => '@media(max-width:640px)',
        ];

        foreach ($queries as $device => $query) {
            $css .= $query.'{.pb-page{'.$this->important($this->pageVariables($settings, $device)).'}}';
        }

        return $css;
    }

    public function classes(array $settings, string $base = ''): string
    {
        $classes = trim($base.' pb-block');
        if (! empty($settings['shadow'])) {
            $classes .= ' pb-shadow';
        }
        if (! empty($settings['classes']) && is_string($settings['classes'])) {
            $classes .= ' '.preg_replace('/[^a-zA-Z0-9_\-\s:\[\]\/]/', '', $settings['classes']);
        }
        return trim($classes);
    }

    private function important(string $declarations): string
    {
        if ($declarations === '') {
            return '';
        }

        return implode('!important;', explode(';', $declarations)).'!important';
    }

    private function quoteFont(string $family): string
    {
        return str_replace([';', '{', '}', '<', '>', ':', '"', "'", '\\'], '', $family);
    }

    private function color(string $value): ?string
    {
        $value = trim($value);
        if (preg_match('/^#[0-9a-fA-F]{3,8}$/', $value)) {
            return $value;
        }
        if (preg_match('/^rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*(,\s*(0|1|0?\.\d+)\s*)?\)$/', $value)) {
            return $value;
        }
        return null;
    }

    private function length(float $value, string $key): string
    {
        if (in_array($key, ['line_height', 'letter_spacing'], true)) {
            return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
        }
        return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.').'px';
    }

    private function integer(string $value, int $min, int $max): int
    {
        return (int) min($max, max($min, (int) $value));
    }
}
