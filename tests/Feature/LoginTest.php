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

        // TODO: move into config file
        config()->set('auth.providers.users', [
            'driver' => 'eloquent',
            'model' => 'Neliserp\Core\User',
        ]);
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
    public function login_requires_valid_username_and_password()
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
    public function inactive_user_cannot_login_even_with_valid_username_and_password()
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
    public function user_can_login_with_valid_username_and_password()
    {
        $this->withoutExceptionHandling();

        $data = [
            'username' => 'user',
            'password' => 'secret',
        ];

        $user = factory(User::class)->create([
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
        ]);

        $this->json('POST', '/api/login', $data)
            ->assertStatus(200);
    }
}
