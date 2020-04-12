<?php

namespace Neliserp\Core\Filters;

abstract class Filter
{
    protected $builder;

    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (!$value) {
                continue;
            }

            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    protected function getFilters()
    {
        return request()->toArray();
    }

    protected function sort($sort)
    {
        return $this->builder->orderBy(request('sort', 'id'), request('order', 'asc'));
    }
}
