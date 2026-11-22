<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Page Builder</title>
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ckeditor/ckeditor5/ckeditor5.css') }}">
    @include('page-builder::partials.fonts')
    @if (config('page-builder.framework') === 'tailwind')
        @vite(['resources/css/app.css','resources/js/app.js'])
    @endif
    @livewireStyles
    @include('page-builder::partials.style')
    <script>
        (function () {
            const stored = localStorage.getItem('theme');
            document.documentElement.classList.toggle('dark', stored ? stored === 'dark' : false);
        })();
    </script>
    <style>
        [x-cloak] { display:none !important; }
        .ck.ck-editor { width: 100% !important; max-width: 100%; }
        .ck.ck-toolbar, .ck.ck-toolbar__items { display: flex !important; flex-wrap: wrap !important; max-width: 100%; }
        .ck.ck-editor__editable_inline { min-height: 120px; max-height: 260px; overflow-y: auto; }
        .ck.ck-label.ck-voice-label { display: none; }
    </style>
</head>
<body x-data="{ darkMode: document.documentElement.classList.contains('dark'), toggleTheme(){ this.darkMode = ! this.darkMode; localStorage.setItem('theme', this.darkMode ? 'dark' : 'light'); } }"
      x-init="$watch('darkMode', v => document.documentElement.classList.toggle('dark', v))"
      :class="darkMode ? 'dark bg-slate-950' : 'bg-slate-50'"
      style="font-family: {{ app(\Elearnac\PageBuilder\Services\FontRegistry::class)->stack(null) }}">
    @if (config('page-builder.editor.component'))
        <div class="hidden" aria-hidden="true">
            <x-dynamic-component :component="config('page-builder.editor.component')"
                                 id="pb-editor-bootstrap"
                                 name="page_builder_bootstrap"
                                 :isFileUpload="false"
                                 :menuBar="false"
                                 :toolbar="config('page-builder.editor.toolbar')" />
        </div>
    @endif
    {{ $slot }}
    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
