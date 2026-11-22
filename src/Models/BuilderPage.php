<?php

namespace Elearnac\PageBuilder\Models;

use Illuminate\Database\Eloquent\Model;

class BuilderPage extends Model
{
    protected $table = 'builder_pages';

    protected $guarded = ['id'];

    protected $casts = [
        'document' => 'array',
        'settings' => 'array',
        'status' => 'boolean',
    ];
}
