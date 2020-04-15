<?php

namespace Neliserp\Core\Filters;

class UserFilter extends Filter
{
    protected function username($username)
    {
        return $this->builder->where('username', 'LIKE', "%{$username}%");
    }

    protected function q($q)
    {
        return $this->builder->where(function ($query) use ($q) {
            $query->where('username', 'LIKE', "%{$q}%");
        });
    }
}
