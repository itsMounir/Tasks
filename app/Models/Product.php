<?php

namespace App\Models;

use App\Scopes\OwnerNameScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasImage;
use App\Scopes\PriceScope;

class Product extends Model
{
    use HasFactory,HasImage;

    protected $guarded = [];

    protected $appends = ['created_from'];


    protected static function booted()
    {
        static::addGlobalScope(new PriceScope);

        if (request()->route()?->getName() == 'categories.show' || request()->route()?->getName() == 'categories.index') {
            static::addGlobalScope(new OwnerNameScope);
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

    protected $with = ['image:id,url,imageable_id'];
    public function category() {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function owner() {
        return $this->belongsTo(User::class,'user_id');
    }

    public function image(){
        return $this->morphMany(Image::class,'imageable');
    }

    public function getCreatedFromAttribute() {
        return $this->created_at->diffForHumans();
    }

}
