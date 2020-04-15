<?php

namespace Neliserp\Core;

class Role extends Model
{
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function addPermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('code', $permission)
                ->firstOrFail();
        }

        return $this->permissions()->save($permission);
    }

    public function syncPermissions($permissions)
    {
        return $this->permissions()->sync($permissions);
    }
}
