<?php

namespace Neliserp\Core\Tests;

use Neliserp\Core\CoreServiceProvider;

trait CoreTest
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            CoreServiceProvider::class,
        ];
    }
}
