<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot() {
        parent::boot();
        static::deleting(function ($image) {
            if (static::where('url',$image->url)->exists())
            {
                Storage::disk('public')->delete($image->url);
            }
        });
    }

    public function imageable()
    {
        return $this->morphTo();
    }
}
