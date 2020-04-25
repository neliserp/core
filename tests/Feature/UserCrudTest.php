<?php

namespace Neliserp\Core\Tests\Feature;

use Neliserp\Core\Tests\CrudTest;
use Neliserp\Core\Tests\CoreTest;

class UserTest extends CrudTest
{
    use CoreTest;

    /**
     * Search 'q' fields
     *
     * @var array
     */
    protected $q_fields = ['username'];
}
