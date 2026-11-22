<?php

use Illuminate\Support\Facades\Route;
use Elearnac\PageBuilder\Http\PreviewController;

Route::get('/page-builder/preview', PreviewController::class)
    ->middleware('web')
    ->name('page-builder.preview');
