<?php

namespace Neliserp\Core;

use Illuminate\Database\Eloquent\Model;
use Neliserp\Core\Filters\PermissionFilter;

class Permission extends Model
{
    protected $guarded = [];

    public function scopeFilter($builder, PermissionFilter $filter)
    {
        return $filter->apply($builder);
    }
}
