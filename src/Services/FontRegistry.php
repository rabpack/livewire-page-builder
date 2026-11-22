<?php

namespace Elearnac\PageBuilder\Services;

final class FontRegistry
{
    public function families(): array
    {
        return (array) config('page-builder.fonts.families', []);
    }

    public function options(): array
    {
        $options = [];
        foreach ($this->families() as $key => $font) {
            $options[$key] = data_get($font, 'label', $key);
        }
        return $options;
    }

    public function stack(?string $key): string
    {
        $key = $key ?: (string) config('page-builder.fonts.default', 'site');
        $font = $this->families()[$key] ?? null;

        if (! $font) {
            return 'system-ui, sans-serif';
        }

        return (string) data_get($font, 'family', 'system-ui, sans-serif');
    }

    public function stylesheets(): array
    {
        return array_values(array_filter((array) config('page-builder.fonts.stylesheets', [])));
    }

    public function cssStack(): string
    {
        return str_replace([';', '{', '}', '<', '>'], '', $this->stack(null));
    }

    public function css(): string
    {
        $rules = [];
        foreach ($this->families() as $font) {
            $sources = array_filter((array) data_get($font, 'src', []));
            if (! $sources) {
                continue;
            }

            $src = [];
            foreach ($sources as $source) {
                $url = $this->cssUrl((string) data_get($source, 'url', ''));
                $format = $this->cssIdentifier((string) data_get($source, 'format', ''));
                if ($url === '') {
                    continue;
                }
                $src[] = "url('".$url."')".($format !== '' ? " format('".$format."')" : '');
            }

            if (! $src) {
                continue;
            }

            $rules[] = '@font-face{font-family:"'.$this->cssIdentifier((string) data_get($font, 'face', data_get($font, 'family', 'sans-serif'))).'"'
                .';src:'.implode(',', $src)
                .';font-style:'.$this->cssIdentifier((string) data_get($font, 'style', 'normal'))
                .';font-weight:'.$this->cssIdentifier((string) data_get($font, 'weight', '400')).';}';
        }

        return implode("\n", $rules);
    }

    private function cssUrl(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }
        if (preg_match('/^[a-z][a-z0-9+.-]*:/i', $value)) {
            return preg_match('#^https?://#i', $value)
                ? str_replace('___COLON___', ':', $this->cssIdentifier(str_replace(':', '___COLON___', $value)))
                : '';
        }

        return $this->cssIdentifier($value);
    }

    private function cssIdentifier(string $value): string
    {
        return str_replace([';', '{', '}', '<', '>', '"', "'", '\\', ':'], '', $value);
    }
}
