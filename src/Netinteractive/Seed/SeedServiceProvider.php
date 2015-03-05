<?php namespace Netinteractive\Seed;

use Illuminate\Support\ServiceProvider;
use Netinteractive\Seed\Commands\TestDataSeedCommand;
use Netinteractive\Testbench\Commands\DataSeedCommand;

/**
 * Class SeedServiceProvider
 * @package Netinteractive\Seed
 */
class SeedServiceProvider extends ServiceProvider {

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

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->package('netinteractive/seeder', 'ni-seeder');

        $this->registerTestDataCmd();

    }

    protected function registerTestDataCmd()
    {
        $this->app->bind('seeder:ni-test-data', function($app)
        {
            return new TestDataSeedCommand();
        });
        $this->commands('seeder:ni-test-data');
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
