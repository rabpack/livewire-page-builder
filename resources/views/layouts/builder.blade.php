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
    <style>
        body { margin:0; font-family: system-ui, sans-serif; background:#f1f5f9; }
        [x-cloak] { display:none !important; }
    </style>
</head>
<body x-data="{ darkMode: false, themeSwitcher(){ const t=localStorage.getItem('theme'); this.darkMode = t ? t==='dark' : false; } }" x-init="themeSwitcher()">
    {{ $slot }}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
