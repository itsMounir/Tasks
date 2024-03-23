<?php

namespace App\Filters;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductsFilters extends BaseFilters
{
    public function price(Builder $query): Builder
    {
        return $query->orderBy('price', $this->request->input('price'));
    }

    public function status(Builder $query): Builder
    {
        return $query->where('status', $this->request->input('status'));
    }

    public function categoryName(Builder $query): Builder
    {
        return $query->join('categories', 'products.category_id', '=', 'categories.id')
            ->orderBy('categories.name', $this->request->input('categoryName'));
    }


    public function productsNumber(Builder $query): Builder
    {
        return $query->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*')
            ->selectSub(function ($query) {
                $query->selectRaw('COUNT(*)')
                    ->from('products')
                    ->whereRaw('products.category_id = categories.id');
            }, 'products_count')
            ->orderBy('products_count', $this->request->input('productsNumber'));
    }
}
