<?php

namespace Elearnac\PageBuilder\Services;

final class ResponsiveSettings
{
    public const DEVICES = ['desktop', 'wide', 'tablet', 'mobile'];

    public function resolve(array $settings, string $device = 'desktop'): array
    {
        if ($device === 'desktop' || ! is_array($settings['responsive'][$device] ?? null)) {
            return $settings;
        }

        return array_merge($settings, $settings['responsive'][$device]);
    }

    public function value(array $settings, string $key, mixed $default = null, string $device = 'desktop'): mixed
    {
        return $this->resolve($settings, $device)[$key] ?? $default;
    }

    public function gridVariables(array $settings): string
    {
        $variables = [];
        $base = (int) ($settings['columns'] ?? 12);
        $defaults = [
            'columns' => $base,
            'columns_wide' => (int) ($settings['columns_wide'] ?? $base),
            'columns_tablet' => (int) ($settings['columns_tablet'] ?? 6),
            'columns_mobile' => (int) ($settings['columns_mobile'] ?? 1),
        ];

        foreach ($defaults as $key => $value) {
            $suffix = $key === 'columns' ? '' : '-'.substr($key, strlen('columns_'));
            $variables['--pb-columns'.$suffix] = $this->clamp($value, 1, 12);
        }

        foreach (['wide', 'tablet', 'mobile'] as $device) {
            $override = $settings['responsive'][$device]['columns'] ?? null;
            if ($override !== null) {
                $variables['--pb-columns-'.$device] = $this->clamp((int) $override, 1, 12);
            }
        }

        return $this->format($variables);
    }

    public function childVariables(array $settings): string
    {
        $variables = [];
        $keys = [
            'column_span' => ['--pb-span', 1, 12],
            'column_offset' => ['--pb-offset', 0, 11],
            'column_order' => ['--pb-order', 0, 12],
        ];

        foreach ($keys as $key => [$variable, $min, $max]) {
            if (array_key_exists($key, $settings)) {
                $variables[$variable] = $this->clamp((int) $settings[$key], $min, $max);
            }
        }

        foreach (['wide', 'tablet', 'mobile'] as $device) {
            $override = $settings['responsive'][$device] ?? [];
            foreach ($keys as $key => [$variable, $min, $max]) {
                if (array_key_exists($key, $override)) {
                    $variables[$variable.'-'.$device] = $this->clamp((int) $override[$key], $min, $max);
                }
            }
        }

        return $this->format($variables);
    }

    private function clamp(int $value, int $min, int $max): int
    {
        return max($min, min($max, $value));
    }

    private function format(array $variables): string
    {
        return implode(';', array_map(
            fn (string $name, int $value): string => $name.':'.$value,
            array_keys($variables),
            array_values($variables)
        ));
    }
}
