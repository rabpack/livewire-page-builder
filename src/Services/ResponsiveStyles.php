<?php

namespace Elearnac\PageBuilder\Services;

final class ResponsiveStyles
{
    private const QUERIES = [
        'wide' => '@media(min-width:1600px)',
        'tablet' => '@media(max-width:1024px)',
        'mobile' => '@media(max-width:640px)',
    ];

    public function __construct(private StyleCompiler $compiler)
    {
    }

    public function documentCss(array $document): string
    {
        $css = '';

        foreach ($this->blocks($document, '') as $path => $settings) {
            $selector = '[data-pb-path="'.$this->escape($path).'"]';
            $base = $this->important($this->compiler->variables($settings, 'desktop'));
            if ($base !== '') {
                $css .= $selector.'{'.$base.'}';
            }

            foreach (self::QUERIES as $device => $query) {
                $declarations = $this->important($this->compiler->variables($settings, $device));
                if ($declarations !== '') {
                    $css .= $query.'{'.$selector.'{'.$declarations.'}}';
                }
            }
        }

        return $css;
    }

    private function blocks(array $document, string $prefix): array
    {
        $blocks = [];

        foreach ($document as $index => $block) {
            if (! is_array($block) || ! isset($block['type'])) {
                continue;
            }

            $path = $prefix === '' ? (string) $index : $prefix.'.'.$index;
            $blocks[$path] = (array) ($block['settings'] ?? []);
            $blocks += $this->blocks((array) ($block['children'] ?? []), $path.'.children');
        }

        return $blocks;
    }

    private function important(string $declarations): string
    {
        if ($declarations === '') {
            return '';
        }

        return implode('!important;', explode(';', $declarations)).'!important';
    }

    private function escape(string $value): string
    {
        return preg_replace('/[^a-zA-Z0-9.\-]/', '', $value) ?? '';
    }
}
