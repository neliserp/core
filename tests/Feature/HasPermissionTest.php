<?php

namespace Neliserp\Core\Tests\Feature;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Route;
use Mockery;

use Neliserp\Core\Http\Middleware\HasPermission;

class HasPermissionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::aliasMiddleware('has_permission', HasPermission::class);
    }

    /** @test */
    public function no_route_name_return_403()
    {
        Route::middleware('has_permission')->any('/_no_route_name', $this->dummy());

        $this->get('/_no_route_name')
            ->assertStatus(403)
            ->assertSee('Unauthorized action. HasPermission get no route name defined');
    }

    /** @test */
    public function invalid_route_name_return_403()
    {
        Route::middleware('has_permission')->any('/_invalid_route_name', $this->dummy())->name('invalid');

        $this->get('/_invalid_route_name')
            ->assertStatus(403)
            ->assertSee("Unauthorized action. HasPermission get invalid route name 'invalid'.");
    }

    /** @test */
    public function invalid_method_return_403()
    {
        Route::middleware('has_permission')->any('/_items_invalid', $this->dummy())->name('items.invalid');

        $this->get('/_items_invalid')
            ->assertStatus(403)
            ->assertSee("Unauthorized action. HasPermission get invalid method 'invalid'.");
    }

    /** @test */
    public function no_policy_class_return_403()
    {
        Route::namespace('Neliserp\Core\Http\Controllers')
            ->middleware('has_permission')
            ->any('/_items', $this->dummy())->name('items.index');

        $this->get('/_items')
            ->assertStatus(403)
            ->assertSee("Unauthorized action. HasPermission has no class 'Neliserp\Core\Policies\ItemPolicy'");
    }

    /** @test */
    public function no_permission_return_403()
    {
        $this->markTestIncomplete();

        Route::namespace('Neliserp\Core\Http\Controllers')
            ->middleware('has_permission')
            ->any('/_items', $this->dummy())->name('items.index');

        Mockery::mock('Neliserp\Core\Policies\ItemPolicy')
            ->shouldReceive('read')
            ->once()
            ->andReturn(false);

        $this->get('/_items')
            ->assertStatus(403)
            ->assertSee("Unauthorized action. HasPermission has no permission 'read'");
    }

    /** @test */
    public function has_permission_return_200()
    {
        $this->markTestIncomplete();

        Route::namespace('Neliserp\Core\Http\Controllers')
            ->middleware('has_permission')
            ->any('/_items', $this->dummy())->name('items.index');

        Mockery::mock('Neliserp\Core\Policies\ItemPolicy')
            ->shouldReceive('read')
            ->once()
            ->andReturn(true);

        $this->get('/_items')
            ->assertStatus(200);
    }

    protected function dummy()
    {
        return '';
    }
}
