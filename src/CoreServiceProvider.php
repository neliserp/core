<?php

namespace Neliserp\Core;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadFactoriesFrom(__DIR__ . '/../database/factories');
    }

    public function register()
    {
        //
    }
}
