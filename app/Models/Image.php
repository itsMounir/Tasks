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
           // dd(static::where('url',$image->url)->exists());
            if (static::where('url',$image->url)->exists())
            {
                Storage::disk('public')->delete($image->url);
            }
        });
    }

    protected $fillable = ['url'];
    public function imageable()
    {
        return $this->morphTo();
    }
}
