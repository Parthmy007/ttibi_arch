<?php

namespace TTIBI\Architecture;

use Illuminate\Support\ServiceProvider;
use TTIBI\Architecture\Commands\CrudGen;

class ArchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CrudGen::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/Stubs' => resource_path('stubs'),
        ]);
    }
}
