<?php

namespace Elearnac\PageBuilder\Livewire\Components;

use Elearnac\PageBuilder\Models\FormSubmission;
use Livewire\Component;

class FormBuilder extends BlockComponent
{
    public array $values = [];
    public bool $sent = false;

    public function fields(): array
    {
        $fields = $this->setting('fields', []);
        if (is_string($fields)) {
            $out = [];
            foreach (explode("\n", $fields) as $line) {
                $parts = array_map('trim', explode('|', $line));
                if (count($parts) >= 2 && $parts[0] !== '') {
                    $out[] = ['name' => $parts[0], 'label' => $parts[1], 'type' => $parts[2] ?? 'text', 'required' => true];
                }
            }
            return $out;
        }
        return is_array($fields) ? array_values($fields) : [];
    }

    public function submit(): void
    {
        $rules = [];
        foreach ($this->fields() as $field) {
            $rule = ($field['required'] ?? true) ? 'required' : 'nullable';
            $rules["values.{$field['name']}"] = $rule.($field['type'] === 'email' ? '|email' : '|string|max:2000');
        }
        $this->validate($rules);
        FormSubmission::create(['form_id' => (string) $this->setting('form_id', 'form'), 'payload' => $this->values]);
        $this->sent = true;
        $this->values = [];
    }

    public function render()
    {
        return view('page-builder::components.form', ['fields' => $this->fields()]);
    }
}
