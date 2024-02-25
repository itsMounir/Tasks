<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasImage;
use App\Scopes\PriceScope;

class Product extends Model
{
    use HasFactory,HasImage;

    protected $guarded = [];

   // protected $with = ['category'];

    protected static function booted()
    {
        static::addGlobalScope(new PriceScope);

        parent::boot();
        if (request()->route()->getName() === 'products.show' || request()->route()->getName() === 'products.index'){
        static::retrieved(function ($product) {
            $product->created_from = $product->created_at->diffForHumans();
            });
        }
    }

    public function scopeFilter($query,array $filters)
    {
        if($filters['search'] ?? false) {
            $query
            ->where(fn($query)=>
            $query
            ->where('name','like','%'.request('search').'%'));
        }

        $query->when($filters['category'] ?? false,fn($query,$category) =>
            $query
                ->whereHas('category',fn($query)=>
                $query->where('category_id',$category)));
    }

    protected $with = ['image'];
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function owner() {
        return $this->belongsTo(User::class,'user_id');
    }

    public function image(){
        return $this->morphMany(Image::class,'imageable');
    }

}
