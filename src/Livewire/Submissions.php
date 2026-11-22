<?php

namespace Elearnac\PageBuilder\Livewire;

use Livewire\Component;
use Elearnac\PageBuilder\Models\FormSubmission;

class Submissions extends Component
{
    public string $formId = '';

    public function render()
    {
        $submissions = FormSubmission::query()
            ->when($this->formId, fn ($query) => $query->where('form_id', $this->formId))
            ->latest()
            ->paginate(20);

        return view('page-builder::components.submissions', compact('submissions'));
    }
}
