<?php

namespace App\Models;

use App\Scopes\OwnerNameScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\Products\Deleted;
use App\Traits\HasImage;
use App\Scopes\PriceScope;

class Product extends Model
{
    use HasFactory,HasImage;

    protected $guarded = [];

    protected $appends = ['created_from','image'];


    protected static function booted()
    {
        static::addGlobalScope(new PriceScope);

        parent::boot();

        static::deleting(function ($product) {
                $product->owner->notify(new Deleted("The product “ $product->name ” has been deleted
                    from the system."));
        });

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

    public function category() {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function owner() {
        return $this->belongsTo(User::class,'user_id');
    }

    public function user() {
        return $this->owner();
    }

    public function image(){
        return $this->morphMany(Image::class,'imageable');
    }

    public function getCreatedFromAttribute() {
        return $this->created_at->diffForHumans();
    }

    public function scopeUsername( Builder $builder ) {
        $builder->whereRelation('user', 'name', 'like', '%a%');
    }

    public function changeStatus(String $status):void {
        $this->status = $status;
        $this->save();
    }
}
