<?php

namespace Neliserp\Core;

use Illuminate\Database\Eloquent\Model;
use Neliserp\Core\Filters\UserFilter;

class User extends Model
{
    protected $guarded = [];

    public function scopeFilter($builder, UserFilter $filter)
    {
        return $filter->apply($builder);
    }
}
