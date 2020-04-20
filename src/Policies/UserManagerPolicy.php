<?php

namespace Neliserp\Core\Policies;

use Neliserp\Core\User;

class UserManagerPolicy
{
    public function read(User $user)
    {
        return $user->hasPermission('user_manager.read') || $user->hasPermission('user_manager.write');
    }

    public function write(User $user)
    {
        return $user->hasPermission('user_manager.write');
    }
}
