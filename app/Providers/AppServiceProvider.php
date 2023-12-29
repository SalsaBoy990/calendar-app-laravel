<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::addNamespace('errors', resource_path('views/errors'));

        Blade::anonymousComponentPath(resource_path('views/global/components'), 'global');
        Blade::anonymousComponentPath(resource_path('views/admin/components'), 'admin');
        Blade::anonymousComponentPath(resource_path('views/public/components'), 'public');
    }
}
