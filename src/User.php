<?php

namespace Neliserp\Core;

use Illuminate\Database\Eloquent\Model;
use Neliserp\Core\Filters\UserFilter;

class User extends Model
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function scopeFilter($builder, UserFilter $filter)
    {
        return $filter->apply($builder);
    }
}
