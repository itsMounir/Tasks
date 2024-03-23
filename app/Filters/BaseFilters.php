<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BaseFilters
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function applyFilters(Builder $query): Builder
    {
        $filters = $this->getFilters();

        foreach ($filters as $filter) {
            if (method_exists($this, $filter)) {
                $query = $this->$filter($query);
            }
        }

        return $query;
    }

    public function name(Builder $query): Builder
    {
        return $query->orderBy('name', $this->request->input('name'));
    }

    public function date(Builder $query): Builder
    {
        return $query->orderBy('created_at', $this->request->input('date'));
    }

    public function productsNumber(Builder $query): Builder
    {
        return $query->withCount('products')->orderBy('products_count', $this->request->input('productsNumber'));
    }

    protected function getFilters(): array
    {
        $filters = [];
        $parameters = $this->request->query();

        foreach ($parameters as $key => $value) {
            $filters[] = $key;
        }

        return $filters;
    }
}
