<?php

namespace Neliserp\Core\Tests\Feature;

use Orchestra\Testbench\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Neliserp\Core\User;
use Neliserp\Core\Permission;
use Neliserp\Core\Role;

class LoginTest extends TestCase
{
    use CoreTest;
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function login_requires_fields()
    {
        $data = [
            'username' => '',
            'password' => '',
        ];

        $this->json('POST', '/api/login', $data)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'username' => [
                        'The username field is required.'
                    ],
                    'password' => [
                        'The password field is required.'
                    ],
                ],
            ]);
    }

    /** @test */
    public function login_with_invalid_username_and_incorrect_password()
    {
        $data = [
            'username' => 'invalid',
            'password' => 'invalid',
        ];

        $this->json('POST', '/api/login', $data)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'username' => [
                        'These credentials do not match our records.',
                    ],
                ],
            ]);
    }

    /** @test */
    public function login_with_valid_username_but_incorrect_password()
    {
        $user = factory(User::class)->create([
            'username' => 'user',
            'password' => bcrypt('secret'),
        ]);

        $data = [
            'username' => 'user',
            'password' => 'invalid',
        ];

        $this->json('POST', '/api/login', $data)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'username' => [
                        'These credentials do not match our records.',
                    ],
                ],
            ]);
    }

    /** @test */
    public function login_with_valid_username_and_password_but_inactive()
    {
        $data = [
            'username' => 'user',
            'password' => 'secret',
        ];

        $user = factory(User::class)->create([
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
            'is_active' => false,
        ]);

        $this->json('POST', '/api/login', $data)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'username' => [
                        'Temporarily disable user account.',
                    ],
                ],
            ]);
    }

    /** @test */
    public function login_with_valid_username_and_password_and_active()
    {
        $data = [
            'username' => 'user',
            'password' => 'secret',
        ];

        $user = factory(User::class)->create([
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
        ]);

        $this->json('POST', '/api/login', $data)
            ->assertStatus(204);
    }
}
