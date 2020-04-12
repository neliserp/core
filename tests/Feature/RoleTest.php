<?php

namespace Neliserp\Core\Tests\Feature;

use Neliserp\Core\CrudTest;
use Neliserp\Core\CoreServiceProvider;
use Neliserp\Core\Role;

class RoleTest extends CrudTest
{
    protected function getPackageProviders($app)
    {
        return [
            CoreServiceProvider::class,
        ];
    }

    // *** index ***

    /** @test */
    public function it_can_list_roles()
    {
        $roles = factory(Role::class, 3)->create();

        $expectedData = $roles->toArray();

        $this->json('GET', '/api/roles?sort=id')
            ->assertStatus(200)
            ->assertJson([
                'data' => $expectedData,
                'links' => [
                    'first' => 'http://localhost/api/roles?page=1',
                    'last' => 'http://localhost/api/roles?page=1',
                    'prev' => null,
                    'next' => null
                ],
                'meta' => [
                    'current_page' => 1,
                    'from' => 1,
                    'last_page' => 1,
                    'path' => 'http://localhost/api/roles',
                    'per_page' => 10,
                    'to' => $roles->count(),
                    'total' => $roles->count(),
                ],
            ]);
    }

    /** @test */
    public function it_can_search_roles_by_code()
    {
        $q_code = 'bbb';

        $role_1 = factory(Role::class)->create(['code' => 'aaa-1']);
        $role_2 = factory(Role::class)->create(['code' => 'bbb-1']);
        $role_3 = factory(Role::class)->create(['code' => 'bbb-2']);
        $role_4 = factory(Role::class)->create(['code' => 'ccc-1']);

        $expectedData = [
            [
                'id' => $role_2->id,
            ],
            [
                'id' => $role_3->id,
            ],
        ];

        $this->json('GET', "/api/roles?code={$q_code}")
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => $expectedData,
            ]);
    }

    // *** show ***

    /** @test */
    public function not_found_roles_return_404()
    {
        $this->json('GET', '/api/roles/9999')
            ->assertStatus(404);
    }

    /** @test */
    public function it_can_view_an_role()
    {
        $role = factory(Role::class)->create();

        $expectedData = $role->toArray();

        $this->json('GET', "/api/roles/{$role->id}")
            ->assertStatus(200)
            ->assertJson([
                'data' => $expectedData,
            ]);
    }

    // *** store ***
    /**  @test */
    public function create_an_role_requires_valid_fields()
    {
        $data = [];

        $this->json('POST', '/api/roles', $data)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ],
                ],
            ]);
    }

    /** @test */
    public function it_can_create_an_role()
    {
        $data = factory(Role::class)->raw();

        $this->json('POST', '/api/roles', $data)
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                ],
            ]);

        $this->assertDatabaseHas('roles', $data);
    }

    // *** update ***

    /**  @test */
    public function update_an_role_requires_valid_fields()
    {
        $role = factory(Role::class)->create();

        $data = [];

        $this->json('PUT', "/api/roles/{$role->id}", $data)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ],
                ],
            ]);
    }

    /** @test */
    public function update_not_found_roles_return_404()
    {
        $data = factory(Role::class)->raw();

        $this->json('PUT', '/api/roles/9999', $data)
            ->assertStatus(404);
    }

    /** @test */
    public function user_can_submit_update_with_no_changes()
    {
        $role = factory(Role::class)->create();

        $data = [
            'code' => $role->code,
            'name' => $role->name,
        ];

        $this->json('PUT', "/api/roles/{$role->id}", $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                ],
            ]);

        $this->assertDatabaseHas('roles', $data);
    }

    /** @test */
    public function it_can_update_an_role()
    {
        $role = factory(Role::class)->create();

        $data = factory(Role::class)->raw();

        $this->json('PUT', "/api/roles/{$role->id}", $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                ],
            ]);

        $this->assertDatabaseHas('roles', $data);
    }

    // *** destroy ***

    /** @test */
    public function delete_not_found_roles_return_404()
    {
        $this->json('DELETE', '/api/roles/9999')
            ->assertStatus(404);
    }

    /** @test */
    public function it_can_delete_an_role()
    {
        $role = factory(Role::class)->create();

        $this->json('DELETE', "/api/roles/{$role->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id,
        ]);
    }
}
