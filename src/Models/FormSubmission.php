<?php

namespace Elearnac\PageBuilder\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $table = 'page_builder_form_submissions';

    protected $guarded = ['id'];

    protected $casts = ['payload' => 'array'];
}
