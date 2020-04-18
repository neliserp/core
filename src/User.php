<?php

namespace Neliserp\Core;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function addRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('code', $role)
                ->firstOrFail();
        }

        return $this->roles()->save($role);
    }

    public function syncRoles($roles)
    {
        return $this->roles()->sync($roles);
    }

    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions()->contains($permission);
        }

        return $this->permissions()->contains($permission->code);
    }

    protected function permissions()
    {
        return $this->roles
            ->map
            ->permissions
            ->flatten()
            ->pluck('code')
            ->unique();
    }
}
