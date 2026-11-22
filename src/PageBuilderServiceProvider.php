<?php

namespace Elearnac\PageBuilder;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class PageBuilderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $defaults = require __DIR__.'/../config/page-builder.php';
        config()->set('page-builder', array_replace_recursive($defaults, config('page-builder', [])));
        $this->app->singleton(ComponentRegistry::class);
        $this->app->singleton(\Elearnac\PageBuilder\Services\RichText::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/page-builder.php' => config_path('page-builder.php'),
        ], 'page-builder-config');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'page-builder');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        Livewire::component('page-builder.builder', \Elearnac\PageBuilder\Livewire\Builder::class);
        Livewire::component('elearnac.page-builder.livewire.builder', \Elearnac\PageBuilder\Livewire\Builder::class);
        Livewire::component('page-builder.renderer', \Elearnac\PageBuilder\Livewire\Renderer::class);
        Livewire::component('elearnac.page-builder.livewire.renderer', \Elearnac\PageBuilder\Livewire\Renderer::class);
        Livewire::component('page-builder.submissions', \Elearnac\PageBuilder\Livewire\Submissions::class);

        foreach (config('page-builder.components', []) as $key => $component) {
            Livewire::component('page-builder.component.'.$key, $component['class']);
        }
    }
}
