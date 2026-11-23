<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Page Builder</title>
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontIcon/all.min.css') }}">
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
        .pb-canvas { min-width: 0; }
        .pb-dropzone {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            border: 2px dashed #5eead4; border-radius: 10px; background: rgba(24,183,165,.05);
            color: #0f766e; text-align: center; cursor: copy;
            transition: min-height .15s ease, background-color .15s ease, border-color .15s ease, opacity .15s ease;
        }
        .pb-dropzone-large { min-height: 168px; gap: .35rem; }
        .pb-dropzone-compact { min-height: 12px; margin: .3rem 0; flex-direction: row; border-width: 1px; opacity: .55; }
        .pb-dropzone-compact:hover { min-height: 34px; opacity: 1; }
        .pb-dropzone-line { display: inline-flex; align-items: center; gap: .3rem; font-size: 10px; font-weight: 700; }
        .pb-dropzone-icon { display: grid; place-items: center; width: 42px; height: 42px; border-radius: 9999px; background: rgba(24,183,165,.12); font-size: 20px; }
        .pb-dropzone-title { font-size: 13px; font-weight: 800; }
        .pb-dropzone-help { font-size: 10px; color: #94a3b8; }
        .pb-dropzone-grid { grid-column: 1 / -1; }
        .pb-drop-active { background: rgba(24, 183, 165, .16) !important; border-color: #0d9488 !important; opacity: 1 !important; }
        .pb-block { position: relative; min-width: 0; padding: 2px; border: 1px solid transparent; border-radius: 8px; transition: border-color .15s ease, box-shadow .15s ease; }
        .pb-grid > .pb-block { min-width: 0; }
        .pb-block:hover { border-color: rgba(24,183,165,.35); }
        .pb-block-selected { border-color: #18b7a5 !important; box-shadow: 0 0 0 2px rgba(24,183,165,.18); }
        .pb-block-hidden { opacity: .42; }
        .pb-block-badge { position: absolute; inset-block-start: -10px; inset-inline-start: 8px; z-index: 30; display: none; align-items: center; gap: .3rem; border-radius: 4px; background: #18b7a5; padding: 2px 6px; color: #fff; font-size: 9px; font-weight: 800; pointer-events: none; }
        .pb-block-tools { position: absolute; inset-block-start: -13px; inset-inline-end: 8px; z-index: 31; display: none; gap: 2px; border: 1px solid #e2e8f0; border-radius: 6px; background: #fff; padding: 2px; box-shadow: 0 4px 12px rgba(15,23,42,.12); }
        .pb-block:hover > .pb-block-badge, .pb-block-selected > .pb-block-badge,
        .pb-block:hover > .pb-block-tools, .pb-block-selected > .pb-block-tools { display: flex; }
        .pb-block-tools button { display: grid; place-items: center; width: 22px; height: 22px; border-radius: 4px; background: #f8fafc; color: #475569; font-size: 9px; }
        .pb-block-tools button:hover { background: #ccfbf1; color: #0f766e; }
        .pb-block-tools .pb-block-delete { background: #e11d48; color: #fff; }
        html.dark .pb-dropzone { border-color: rgba(94,234,212,.45); background: rgba(24,183,165,.06); color: #5eead4; }
        html.dark .pb-dropzone-help { color: #64748b; }
        html.dark .pb-block-tools { border-color: #334155; background: #0f172a; box-shadow: 0 4px 12px rgba(0,0,0,.4); }
        html.dark .pb-block-tools button { background: #1e293b; color: #cbd5e1; }
        [data-page-builder-preview] [id*="trustseal"],
        [data-page-builder-preview] [class*="trustseal"],
        [data-page-builder-preview] [class*="trust-seal"],
        [data-page-builder-preview] iframe[src*="trustseal"] { display: none !important; }
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
    <x-toaster-hub/>
    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    <script data-page-builder-echo-stub>
        if (! window.Echo) {
            const channel = { listen: () => channel, stopListening: () => channel, notification: () => channel, whisper: () => channel };
            window.Echo = {
                socketId: () => null,
                channel: () => channel,
                private: () => channel,
                encryptedPrivate: () => channel,
                join: () => channel,
                leave: () => channel,
                listen: () => channel,
            };
        }
    </script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
