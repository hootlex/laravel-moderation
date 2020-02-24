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
        Status::$PENDING = config('moderation.statuses.pending', 0);
        Status::$APPROVED = config('moderation.statuses.approved', 1);
        Status::$REJECTED = config('moderation.statuses.rejected', 2);
        Status::$POSTPONED = config('moderation.statuses.postponed', 3);
    }
}
