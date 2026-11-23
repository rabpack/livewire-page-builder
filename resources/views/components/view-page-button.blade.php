@props([
    'url' => null,
    'slug' => '',
    'class' => '',
])

@if ($url)
    <a href="{{ $url }}"
       target="_blank"
       rel="noopener noreferrer"
       title="باز کردن صفحه در تب جدید"
       aria-label="باز کردن صفحه"
       data-page-public-link="{{ $url }}"
       data-page-public-slug="{{ $slug }}"
       @class([
           'group inline-grid size-9 shrink-0 place-items-center rounded-lg border border-brand-500/30 bg-brand-500/10 text-brand-600 transition-all duration-200 hover:border-brand-500 hover:bg-brand-500 hover:text-white hover:shadow-sm hover:shadow-brand-500/20 dark:text-brand-200',
           $class,
       ])>
        <i class="far fa-external-link text-xs"></i>
    </a>
@endif
