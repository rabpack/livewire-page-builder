<?php

namespace Elearnac\PageBuilder\Support;

use Elearnac\PageBuilder\Livewire\Components\UniversalBlock;

final class ComponentBlueprint
{
    public static function make(
        string $label,
        string $group,
        string $icon,
        string $view,
        array $fields,
        array $extra = []
    ): array {
        return array_merge([
            'group' => $group,
            'label' => $label,
            'icon' => $icon,
            'class' => UniversalBlock::class,
            'view' => $view,
            'fields' => $fields,
        ], $extra);
    }
}
