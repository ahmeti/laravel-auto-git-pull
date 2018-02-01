<?php 

namespace Ahmeti\LaravelAutoGitPull\Providers;

use Illuminate\Support\ServiceProvider;
use Ahmeti\LaravelAutoGitPull\Controllers\LaravelAutoGitPullController;

class LaravelAutoGitPullServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['router']->any('auto-git-pull', function(){
            dd('dssdadsaads');
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('laravel-auto-git-pull', function () {
            return new LaravelAutoGitPullController();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravel-auto-git-pull'];
    }
}