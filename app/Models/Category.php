<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Builder,
    Model
};
use App\Traits\HasImage;

class Category extends Model
{
    use HasFactory,HasImage;

    protected static function booted() {
        static::deleting(function ($category) {
            if (! $category->childrens->isEmpty()) {
                foreach ($category->childrens as $child) {
                    $child->delete();
                }
            }
        });
    }

    protected $fillable = ['name','parent_id'];

    protected $appends = ['created_from','image'];

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function parent() {
        return $this->belongsTo(Category::class,'parent_id');
    }

    public function childrens() {
        return $this->hasMany(Category::class,'parent_id')
        ->select([
            'id',
            'name',
            'parent_id',
            'created_at'
        ])->with(['childrens', 'products.user']);
    }

    public function getCreatedFromAttribute() {
        return $this->created_at->diffForHumans();
    }

    public function scopeParent(Builder $builder) {
        $builder->whereNull('parent_id');
    }
}
