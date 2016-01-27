<?php

namespace Hootlex\Moderation;

use Illuminate\Support\ServiceProvider;

class ModerationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/moderation.php' => config_path('moderation.php')
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
