<?php

namespace Moh6mmad\LaravelBlog;

use Moh6mmad\LaravelBlog\Console\InstallLaravelBlog;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->make('Moh6mmad\LaravelBlog\Http\Controllers\LaravelBlogController');
        $this->app->make('Moh6mmad\LaravelBlog\Http\Models\LaravelBlog');
        $this->loadViewsFrom(__DIR__.'/views', 'laravel-settings');
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        include __DIR__.'/routes/routes.php';
    }
}
