<?php

namespace Elearnac\PageBuilder\Services;

final class DocumentTree
{
    public function parent(string $path): ?string
    {
        if ($path === '' || ! preg_match('/^(.*)\.children\.(\d+)$/', $path, $matches)) {
            return null;
        }

        return $matches[1];
    }

    public function index(string $path): int
    {
        if ($path === '' || ! preg_match('/\.children\.(\d+)$/', $path, $matches)) {
            return is_numeric($path) ? (int) $path : 0;
        }

        return (int) $matches[1];
    }

    public function childrenPath(string $parent): string
    {
        return $parent === '' ? '' : $parent.'.children';
    }

    public function childPath(string $parent, int $index): string
    {
        return $parent === '' ? (string) $index : $parent.'.children.'.$index;
    }

    public function isDescendant(string $path, string $ancestor): bool
    {
        return $ancestor !== '' && str_starts_with($path, $ancestor.'.children.');
    }

    public function prune(array $document): array
    {
        $blocks = [];

        foreach ($document as $block) {
            if (! is_array($block) || ! isset($block['type']) || ! is_string($block['type'])) {
                continue;
            }

            if (isset($block['children']) && is_array($block['children'])) {
                $block['children'] = $this->prune($block['children']);
            }

            $blocks[] = $block;
        }

        return $blocks;
    }

    public function get(array $document, string $path): ?array
    {
        $block = data_get($document, $path);

        return is_array($block) ? $block : null;
    }

    public function value(array $document, string $path, mixed $default = null): mixed
    {
        return data_get($document, $path, $default);
    }

    public function setValue(array &$document, string $path, mixed $value): void
    {
        data_set($document, $path, $value);
    }

    public function insert(array &$document, string $parent, int $index, array $block): string
    {
        $items = $this->list($document, $parent);
        $index = max(0, min($index, count($items)));
        array_splice($items, $index, 0, [$block]);
        $this->writeList($document, $parent, $items);

        return $this->childPath($parent, $index);
    }

    public function remove(array &$document, string $path): ?array
    {
        $parent = $this->parent($path) ?? '';
        $index = $this->index($path);
        $items = $this->list($document, $parent);

        if (! isset($items[$index])) {
            return null;
        }

        $removed = $items[$index];
        unset($items[$index]);
        $this->writeList($document, $parent, array_values($items));

        return ['block' => $removed, 'parent' => $parent, 'index' => $index];
    }

    public function duplicate(array &$document, string $path): ?string
    {
        $block = $this->get($document, $path);
        if (! $block) {
            return null;
        }

        $parent = $this->parent($path) ?? '';
        $copy = json_decode(json_encode($block), true);

        return $this->insert($document, $parent, $this->index($path) + 1, $copy);
    }

    public function move(array &$document, string $source, string $targetParent, int $index): ?string
    {
        if ($source === '' || $this->isDescendant($targetParent, $source)) {
            return null;
        }

        $sourceParent = $this->parent($source) ?? '';
        $sourceIndex = $this->index($source);
        $removed = $this->remove($document, $source);
        if (! $removed) {
            return null;
        }

        if ($sourceParent === $targetParent && $sourceIndex < $index) {
            $index--;
        }

        return $this->insert($document, $targetParent, $index, $removed['block']);
    }

    public function swap(array &$document, string $path, int $direction): ?string
    {
        $parent = $this->parent($path) ?? '';
        $index = $this->index($path);
        $items = $this->list($document, $parent);
        $target = $index + $direction;

        if (! isset($items[$index], $items[$target])) {
            return null;
        }

        [$items[$index], $items[$target]] = [$items[$target], $items[$index]];
        $this->writeList($document, $parent, $items);

        return $this->childPath($parent, $target);
    }

    public function countChildren(array $document, string $parent): int
    {
        return count($this->list($document, $parent));
    }

    public function siblingAfterRemoval(string $parent, int $index, int $count): string
    {
        if ($count === 0) {
            return $parent;
        }

        return $this->childPath($parent, min($index, $count - 1));
    }

    private function list(array $document, string $parent): array
    {
        $path = $this->childrenPath($parent);

        return $path === '' ? $document : (array) data_get($document, $path, []);
    }

    private function writeList(array &$document, string $parent, array $items): void
    {
        $path = $this->childrenPath($parent);
        if ($path === '') {
            $document = $items;
            return;
        }

        data_set($document, $path, $items);
    }
}
