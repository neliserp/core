<?php

namespace Neliserp\Core\Tests\Feature;

use Illuminate\Database\Eloquent\Collection;

use Neliserp\Core\Tests\CrudTest;
use Neliserp\Core\Role;
use Neliserp\Core\Permission;

class RoleTest extends CrudTest
{
    use CoreTest;

    /**
     * Search 'q' fields
     *
     * @var array
     */
    protected $q_fields = ['code', 'name'];

    /** @test */
    public function role_has_permissions()
    {
        $role = factory(Role::class)->create();

        $this->assertInstanceOf(Collection::class, $role->permissions);
    }

    /** @test */
    public function role_has_users()
    {
        $role = factory(Role::class)->create();

        $this->assertInstanceOf(Collection::class, $role->users);
    }

    /** @test */
    public function role_can_add_permission()
    {
        $role = factory(Role::class)->create();
        $permission_1 = factory(Permission::class)->create();
        $permission_2 = factory(Permission::class)->create();

        $role->addPermission($permission_1->code);
        $role->addPermission($permission_2);

        $this->assertDatabaseHas('permission_role', [
            'permission_id' => $permission_1->id,
            'role_id' => $role->id,
        ]);

        $this->assertDatabaseHas('permission_role', [
            'permission_id' => $permission_2->id,
            'role_id' => $role->id,
        ]);
    }

    /** @test */
    public function role_can_sync_permissions()
    {
        $role = factory(Role::class)->create();
        $permission_1 = factory(Permission::class)->create();
        $permission_2 = factory(Permission::class)->create();
        $permission_3 = factory(Permission::class)->create();

        $role->permissions()->save($permission_1);

        $role->syncPermissions([$permission_2->id, $permission_3->id]);

        $this->assertDatabaseMissing('permission_role', [
            'permission_id' => $permission_1->id,
            'role_id' => $role->id,
        ]);

        $this->assertDatabaseHas('permission_role', [
            'permission_id' => $permission_2->id,
            'role_id' => $role->id,
        ]);

        $this->assertDatabaseHas('permission_role', [
            'permission_id' => $permission_3->id,
            'role_id' => $role->id,
        ]);
    }
}
