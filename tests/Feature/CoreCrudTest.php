<?php

namespace Neliserp\Core\Tests\Feature;

use Neliserp\Core\Tests\CrudTest;
use Neliserp\Core\CoreServiceProvider;

abstract class CoreCrudTest extends CrudTest
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
