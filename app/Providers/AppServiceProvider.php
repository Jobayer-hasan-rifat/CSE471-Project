<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register layout components
        Blade::component('layouts.admin', \App\View\Components\Layouts\Admin::class);
        Blade::component('layouts.club', \App\View\Components\Layouts\Club::class);
        Blade::component('layouts.oca', \App\View\Components\Layouts\Oca::class);
    }
}
