<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($image) {
            // dd($image->url);
            // dd(static::where('url',$image->url)->exists());
            if (static::where('url',$image->url)->exists())
            {
                Storage::disk('public')->delete($image->url);
            }
        });
    }

    protected $guarded = [];

    protected function url(): Attribute
    {
        if (! (request()->route()->getName() == 'categories.destroy' || request()->route()->getName() == 'users.destroy' || request()->route()->getName() == 'products.destroy')) {
            return Attribute::make(
                get: fn (string $value) => asset($value),
            );
        }
        else {
            return Attribute::make(
                get: fn (string $value) => $value,
            );
        }

    }

    public function imageable()
    {
        return $this->morphTo();
    }
}
