<?php

namespace Sciarcinski\LaravelMenu;

use Illuminate\Support\ServiceProvider;
use Sciarcinski\LaravelMenu\Generators\MenuMakeCommand;

class MenusServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('menu', function ($app) {
            return new Menu($app);
        });
    }
    
    public function boot()
    {
        $this->commands(MenuMakeCommand::class);
    }
    
    /**
     *  Get the services provided by the provider.
     * 
     * @return array
     */
    public function provides()
    {
        return ['menu'];
    }
}
