<?php

namespace Ironopolis\Skeleton;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use DB;
use Request;
use Ironopolis\Skeleton\Body;
use Illuminate\Support\Facades\Route;
use Ironopolis\Skeleton\Helpers\Meta;
use Exception;
use Storage;

class SkeletonServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'skeleton');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->publishes([
            __DIR__.'/resources/assets' => base_path('resources/assets/packages/skeleton/'),
        ], 'public');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }
}
