<?php

namespace Neliserp\Core\Tests\Feature;

use Illuminate\Database\Eloquent\Collection;

use Neliserp\Core\Permission;

class PermissionTest extends CoreCrudTest
{
    /**
     * Search 'q' fields
     *
     * @var array
     */
    protected $q_fields = ['code', 'name'];

    /** @test */
    public function permission_has_roles()
    {
        $permission = factory(Permission::class)->create();

        $this->assertInstanceOf(Collection::class, $permission->roles);
    }
}
