<?php

namespace Nbz4live\LaravelGeoIP2;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Nbz4live\LaravelGeoIP2\Console\UpdateCommand;

class ServiceProvider extends BaseServiceProvider
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
        $configPath = __DIR__ . '/config/geoip2.php';
        $this->publishes([$configPath => config_path('geoip2.php')]);

        $this->mergeConfigFrom($configPath, 'geoip2');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('geoip2', function ($app) {
            return new GeoIP2($app['config'], $app['request']);
        });

        $this->app->singleton('command.geoip2.update', function ($app) {
            return new UpdateCommand($app['config']);
        });

        $this->commands(array('command.geoip2.update'));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('geoip2', 'command.geoip2.update');
    }
}
