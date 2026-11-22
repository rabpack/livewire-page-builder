<?php

namespace Elearnac\PageBuilder\Http;

class PreviewController
{
    public function __invoke()
    {
        $draft = session('page-builder.draft', []);

        return view('page-builder::preview', [
            'title' => $draft['title'] ?? 'پیشنمایش صفحه',
            'blocks' => $draft['document'] ?? [],
            'settings' => $draft['settings'] ?? [],
        ]);
    }
}
