<?php namespace Netinteractive\Seed;

use Illuminate\Support\ServiceProvider;
use Netinteractive\Seed\Commands\TestDataSeedCommand;

/**
 * Class SeedServiceProvider
 * @package Netinteractive\Seed
 */
class SeedServiceProvider extends ServiceProvider
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
        $this->publishes(array(
            __DIR__.'/../../config/test.php' => config_path('/packages/netinteractive/seed/test.php'),
        ), 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTestDataCmd();
    }

    protected function registerTestDataCmd()
    {
        $this->app->bind('seed:ni-test-data', function($app)
        {
            return new TestDataSeedCommand();
        });


        $this->commands('seed:ni-test-data');
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
