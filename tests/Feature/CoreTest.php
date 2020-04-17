<?php

namespace Neliserp\Core\Tests\Feature;

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
