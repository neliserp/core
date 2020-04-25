<?php

namespace Neliserp\Core\Tests\Feature;

use Neliserp\Core\Tests\CrudTest;
use Neliserp\Core\Tests\CoreTest;

class RoleCrudTest extends CrudTest
{
    use CoreTest;

    /**
     * Search 'q' fields
     *
     * @var array
     */
    protected $q_fields = ['code', 'name'];
}
