<?php

namespace Elearnac\PageBuilder\Livewire;

use Livewire\Component;
use Elearnac\PageBuilder\Models\FormSubmission;

class Submissions extends Component
{
    public string $formId = '';

    public function mount(): void
    {
        $permission = config('page-builder.submissions.permission');

        if ($permission) {
            abort_unless(auth()->user()?->can($permission), 403);
        }
    }

    public function render()
    {
        $submissions = FormSubmission::query()
            ->when($this->formId, fn ($query) => $query->where('form_id', $this->formId))
            ->latest()
            ->paginate(20);

        return view('page-builder::components.submissions', [
            'submissions' => $submissions,
            'forms' => FormSubmission::query()->distinct()->orderBy('form_id')->pluck('form_id')->all(),
        ]);
    }
}
