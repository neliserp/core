<?php

namespace Neliserp\Core;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function scopeFilter($builder)
    {
        $filter = $this->getFilterClassName();

        return (new $filter)->apply($builder);
    }

    protected function getFilterClassName()
    {
        $reflection = new \ReflectionClass($this);
        $namespace_name = $reflection->getNamespaceName();  // Neliserp\Core
        $short_class_name = $reflection->getShortName();

        return "{$namespace_name}\\Filters\\{$short_class_name}Filter";
    }
}
