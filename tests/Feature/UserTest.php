<?php

namespace Neliserp\Core\Tests\Feature;

use Neliserp\Core\CrudTest;
use Neliserp\Core\CoreServiceProvider;
use Neliserp\Core\User;

class UserTest extends CrudTest
{
    protected function getPackageProviders($app)
    {
        return [
            CoreServiceProvider::class,
        ];
    }

    // *** index ***

    /** @test */
    public function it_can_list_users()
    {
        $users = factory(User::class, 3)->create();

        $expectedData = $users->toArray();

        $this->json('GET', '/api/users?sort=id')
            ->assertStatus(200)
            ->assertJson([
                'data' => $expectedData,
                'links' => [
                    'first' => 'http://localhost/api/users?page=1',
                    'last' => 'http://localhost/api/users?page=1',
                    'prev' => null,
                    'next' => null
                ],
                'meta' => [
                    'current_page' => 1,
                    'from' => 1,
                    'last_page' => 1,
                    'path' => 'http://localhost/api/users',
                    'per_page' => 10,
                    'to' => $users->count(),
                    'total' => $users->count(),
                ],
            ]);
    }

    /** @test */
    public function it_can_search_users_by_code()
    {
        $q_code = 'bbb';

        $user_1 = factory(User::class)->create(['code' => 'aaa-1']);
        $user_2 = factory(User::class)->create(['code' => 'bbb-1']);
        $user_3 = factory(User::class)->create(['code' => 'bbb-2']);
        $user_4 = factory(User::class)->create(['code' => 'ccc-1']);

        $expectedData = [
            [
                'id' => $user_2->id,
            ],
            [
                'id' => $user_3->id,
            ],
        ];

        $this->json('GET', "/api/users?code={$q_code}")
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => $expectedData,
            ]);
    }

    // *** show ***

    /** @test */
    public function not_found_users_return_404()
    {
        $this->json('GET', '/api/users/9999')
            ->assertStatus(404);
    }

    /** @test */
    public function it_can_view_an_user()
    {
        $user = factory(User::class)->create();

        $expectedData = $user->toArray();

        $this->json('GET', "/api/users/{$user->id}")
            ->assertStatus(200)
            ->assertJson([
                'data' => $expectedData,
            ]);
    }

    // *** store ***
    /**  @test */
    public function create_an_user_requires_valid_fields()
    {
        $data = [];

        $this->json('POST', '/api/users', $data)
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
    public function it_can_create_an_user()
    {
        $data = factory(User::class)->raw();

        $this->json('POST', '/api/users', $data)
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                ],
            ]);

        $this->assertDatabaseHas('users', $data);
    }

    // *** update ***

    /**  @test */
    public function update_an_user_requires_valid_fields()
    {
        $user = factory(User::class)->create();

        $data = [];

        $this->json('PUT', "/api/users/{$user->id}", $data)
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
    public function update_not_found_users_return_404()
    {
        $data = factory(User::class)->raw();

        $this->json('PUT', '/api/users/9999', $data)
            ->assertStatus(404);
    }

    /** @test */
    public function user_can_submit_update_with_no_changes()
    {
        $user = factory(User::class)->create();

        $data = [
            'code' => $user->code,
            'name' => $user->name,
        ];

        $this->json('PUT', "/api/users/{$user->id}", $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                ],
            ]);

        $this->assertDatabaseHas('users', $data);
    }

    /** @test */
    public function it_can_update_an_user()
    {
        $user = factory(User::class)->create();

        $data = factory(User::class)->raw();

        $this->json('PUT', "/api/users/{$user->id}", $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                ],
            ]);

        $this->assertDatabaseHas('users', $data);
    }

    // *** destroy ***

    /** @test */
    public function delete_not_found_users_return_404()
    {
        $this->json('DELETE', '/api/users/9999')
            ->assertStatus(404);
    }

    /** @test */
    public function it_can_delete_an_user()
    {
        $user = factory(User::class)->create();

        $this->json('DELETE', "/api/users/{$user->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
