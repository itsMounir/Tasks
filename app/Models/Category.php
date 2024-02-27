<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    protected $with = ['image:id,url,imageable_id','childrens','products'];

    protected $guarded = [];

    protected $appends = ['created_from'];

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function parent() {
        return $this->belongsTo(Category::class,'parent_id');
    }

    public function childrens() {
        return $this->hasMany(Category::class,'parent_id');
    }

    public function getCreatedFromAttribute() {
        return $this->created_at->diffForHumans();
    }
}
