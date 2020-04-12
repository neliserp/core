<?php

namespace Neliserp\Core;

use Illuminate\Database\Eloquent\Model;
use Neliserp\Core\Filters\RoleFilter;

class Role extends Model
{
    protected $guarded = [];

    public function scopeFilter($builder, RoleFilter $filter)
    {
        return $filter->apply($builder);
    }
}
