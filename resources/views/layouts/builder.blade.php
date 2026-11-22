<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Page Builder</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    @if (config('page-builder.framework') === 'tailwind')
        @vite(['resources/css/app.css','resources/js/app.js'])
    @endif
    @livewireStyles
    <script>
        (function () {
            const stored = localStorage.getItem('theme');
            document.documentElement.classList.toggle('dark', stored ? stored === 'dark' : false);
        })();
    </script>
    <style>
        body { margin:0; font-family: system-ui, sans-serif; }
        [x-cloak] { display:none !important; }
    </style>
</head>
<body x-data="{ darkMode: document.documentElement.classList.contains('dark'), toggleTheme(){ this.darkMode = ! this.darkMode; localStorage.setItem('theme', this.darkMode ? 'dark' : 'light'); } }" x-init="$watch('darkMode', v => document.documentElement.classList.toggle('dark', v))" :class="darkMode ? 'dark bg-slate-950' : 'bg-slate-50'">
    {{ $slot }}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
