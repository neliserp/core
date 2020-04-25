<?php

namespace Neliserp\Core\Tests\Unit;

use Orchestra\Testbench\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Neliserp\Core\Permission;
use Neliserp\Core\Tests\CoreTest;

class PermissionTest extends TestCase
{
    use CoreTest;
    use RefreshDatabase;

    /** @test */
    public function permission_has_roles()
    {
        $permission = factory(Permission::class)->create();

        $this->assertInstanceOf(Collection::class, $permission->roles);
    }
}
