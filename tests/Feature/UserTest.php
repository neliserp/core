<?php

namespace Neliserp\Core\Tests\Feature;

use Illuminate\Database\Eloquent\Collection;

use Neliserp\Core\User;
use Neliserp\Core\Role;
use Neliserp\Core\Permission;

class UserTest extends CoreCrudTest
{
    /**
     * Search 'q' fields
     *
     * @var array
     */
    protected $q_fields = ['username'];

    /** @test */
    public function user_has_roles()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(Collection::class, $user->roles);
    }

    /** @test */
    public function user_can_add_role()
    {
        $user = factory(User::class)->create();
        $role_1 = factory(Role::class)->create();
        $role_2 = factory(Role::class)->create();

        $user->addRole($role_1->code);
        $user->addRole($role_2);

        $this->assertDatabaseHas('role_user', [
            'role_id' => $role_1->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('role_user', [
            'role_id' => $role_2->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function user_can_sync_roles()
    {
        $user = factory(User::class)->create();
        $role_1 = factory(Role::class)->create();
        $role_2 = factory(Role::class)->create();
        $role_3 = factory(Role::class)->create();

        $user->roles()->save($role_1);

        $user->syncRoles([$role_2->id, $role_3->id]);

        $this->assertDatabaseMissing('role_user', [
            'role_id' => $role_1->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('role_user', [
            'role_id' => $role_2->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('role_user', [
            'role_id' => $role_3->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function user_can_check_has_permission()
    {
        $user = factory(User::class)->create();
        $permission = factory(Permission::class)->create();
        $role = factory(Role::class)->create();

        $role->permissions()->save($permission);
        $user->roles()->save($role);

        $this->assertTrue($user->hasPermission($permission->code));
        $this->assertTrue($user->hasPermission($permission));
    }
}
