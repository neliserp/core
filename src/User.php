<?php

namespace Neliserp\Core;

class User extends Model
{
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
