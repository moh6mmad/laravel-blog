<?php

namespace Moh6mmad\LaravelBlog;

use Moh6mmad\LaravelBlog\Console\InstallLaravelBlog;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('laravel-blog.php');
    }

    public function register()
    {
        $this->app->make('Moh6mmad\LaravelBlog\Http\Controllers\LaravelBlogController');
        $this->app->make('Moh6mmad\LaravelBlog\Http\Models\LaravelBlog');
        $this->loadViewsFrom(__DIR__ . '/views', 'laravel-settings');
    }

    public function boot()
    {
        $configPath = __DIR__ . '/config/laravel-blog.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        include __DIR__ . '/routes/routes.php';
    }
}
