<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasImage;

class Category extends Model
{
    use HasFactory,HasImage;

    protected static function booted()
    {
        parent::boot();

        if (request()->route()->getName() === 'categories.show' || request()->route()->getName() === 'categories.index'){
        static::retrieved(function ($category) {
            $category->created_from = $category->created_at->diffForHumans();
            });
        }
    }

    protected $guarded = [];

    public function products() {
        return $this->hasMany(Product::class);
    }
}
