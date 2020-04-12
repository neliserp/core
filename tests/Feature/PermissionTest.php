<?php

namespace Tests\Feature;

use Orchestra\Testbench\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Neliserp\Core\CoreServiceProvider;
use Neliserp\Core\Permission;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            CoreServiceProvider::class,
        ];
    }

    // *** index ***

    /** @test */
    public function it_can_list_permissions()
    {
        $permissions = factory(Permission::class, 3)->create();

        $expectedData = $permissions->toArray();

        $this->json('GET', '/api/permissions?sort=id')
            ->assertStatus(200)
            ->assertJson([
                'data' => $expectedData,
                'links' => [
                    'first' => 'http://localhost/api/permissions?page=1',
                    'last' => 'http://localhost/api/permissions?page=1',
                    'prev' => null,
                    'next' => null
                ],
                'meta' => [
                    'current_page' => 1,
                    'from' => 1,
                    'last_page' => 1,
                    'path' => 'http://localhost/api/permissions',
                    'per_page' => 10,
                    'to' => $permissions->count(),
                    'total' => $permissions->count(),
                ],
            ]);
    }

    /** @test */
    public function it_can_search_permissions_by_code()
    {
        $q_code = 'bbb';

        $permission_1 = factory(Permission::class)->create(['code' => 'aaa-1']);
        $permission_2 = factory(Permission::class)->create(['code' => 'bbb-1']);
        $permission_3 = factory(Permission::class)->create(['code' => 'bbb-2']);
        $permission_4 = factory(Permission::class)->create(['code' => 'ccc-1']);

        $expectedData = [
            [
                'id' => $permission_2->id,
            ],
            [
                'id' => $permission_3->id,
            ],
        ];

        $this->json('GET', "/api/permissions?code={$q_code}")
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => $expectedData,
            ]);
    }

    // *** show ***

    /** @test */
    public function not_found_permissions_return_404()
    {
        $this->json('GET', '/api/permissions/9999')
            ->assertStatus(404);
    }

    /** @test */
    public function it_can_view_an_permission()
    {
        $permission = factory(Permission::class)->create();

        $expectedData = $permission->toArray();

        $this->json('GET', "/api/permissions/{$permission->id}")
            ->assertStatus(200)
            ->assertJson([
                'data' => $expectedData,
            ]);
    }

    // *** store ***
    /**  @test */
    public function create_an_permission_requires_valid_fields()
    {
        $data = [];

        $this->json('POST', '/api/permissions', $data)
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
    public function it_can_create_an_permission()
    {
        $data = factory(Permission::class)->raw();

        $this->json('POST', '/api/permissions', $data)
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                ],
            ]);

        $this->assertDatabaseHas('permissions', $data);
    }

    // *** update ***

    /**  @test */
    public function update_an_permission_requires_valid_fields()
    {
        $permission = factory(Permission::class)->create();

        $data = [];

        $this->json('PUT', "/api/permissions/{$permission->id}", $data)
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
    public function update_not_found_permissions_return_404()
    {
        $data = factory(Permission::class)->raw();

        $this->json('PUT', '/api/permissions/9999', $data)
            ->assertStatus(404);
    }

    /** @test */
    public function user_can_submit_update_with_no_changes()
    {
        $permission = factory(Permission::class)->create();

        $data = [
            'code' => $permission->code,
            'name' => $permission->name,
        ];

        $this->json('PUT', "/api/permissions/{$permission->id}", $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                ],
            ]);

        $this->assertDatabaseHas('permissions', $data);
    }

    /** @test */
    public function it_can_update_an_permission()
    {
        $permission = factory(Permission::class)->create();

        $data = factory(Permission::class)->raw();

        $this->json('PUT', "/api/permissions/{$permission->id}", $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                ],
            ]);

        $this->assertDatabaseHas('permissions', $data);
    }

    // *** destroy ***

    /** @test */
    public function delete_not_found_permissions_return_404()
    {
        $this->json('DELETE', '/api/permissions/9999')
            ->assertStatus(404);
    }

    /** @test */
    public function it_can_delete_an_permission()
    {
        $permission = factory(Permission::class)->create();

        $this->json('DELETE', "/api/permissions/{$permission->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('permissions', [
            'id' => $permission->id,
        ]);
    }
}
