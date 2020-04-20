<?php

namespace Neliserp\Core\Policies;

use Neliserp\Core\User;
use Illuminate\Support\Str;

abstract class CrudPolicy
{
    public function read(User $user)
    {
        $table = $this->getTable();

        return $user->hasPermission("{$table}.read") || $user->hasPermission("{$table}.write");
    }

    public function write(User $user)
    {
        $table = $this->getTable();

        return $user->hasPermission("{$table}.write");
    }

    protected function getTable()
    {
        $reflection = new \ReflectionClass($this);
        $short_class_name = $reflection->getShortName();
        $model_name = str_replace('Policy', '', $short_class_name);

        return Str::of($model_name)->plural()->lower();
    }
}
