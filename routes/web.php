<?php

use Illuminate\Support\Facades\Route;
use Elearnac\PageBuilder\Http\PreviewController;

Route::get('/page-builder/preview', PreviewController::class)
    ->middleware('web')
    ->name('page-builder.preview');

Route::get('/page-builder/submissions', config('page-builder.submissions.component', \Elearnac\PageBuilder\Livewire\Submissions::class))
    ->middleware(config('page-builder.submissions.middleware', ['web', 'auth']))
    ->name('page-builder.submissions');
