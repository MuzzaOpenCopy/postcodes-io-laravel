<?php namespace Chadanuk\PostcodesIo;

use Illuminate\Support\ServiceProvider;

class PostcodesIoServiceProvider extends ServiceProvider
{
    /**
    * Indicates if loading of the provider is deferred.
    *
    * @var bool
    */
    protected $defer = false;

    /**
    * Bootstrap the application events.
    *
    * @return void
    */
    public function boot()
    {
        $this->package('chadanuk/postcodes-io');
    }

    /**
    * Register the service provider.
    *
    * @return void
    */
    public function register()
    {
        $this->app->bind('chadanuk.postcodes-io', function () {
            return (new ApiClientFactory)->create();
        });
    }

    /**
    * Get the services provided by the provider.
    *
    * @return array
    */
    public function provides()
    {
        return array();
    }
}
