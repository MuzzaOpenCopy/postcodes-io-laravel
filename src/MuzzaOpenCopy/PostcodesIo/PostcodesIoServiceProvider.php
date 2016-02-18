<?php namespace MuzzaOpenCopy\PostcodesIo;

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
    * Register the service provider.
    *
    * @return void
    */
    public function register()
    {
        $this->app->bind('muzzaopencopy.postcodes-io', function () {
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
