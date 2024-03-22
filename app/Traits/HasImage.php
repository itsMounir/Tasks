<?php

namespace App\Traits;

use App\Models\Image;
use App\Models\Product;

trait HasImage
{

    public static function bootHasImage()
    {
        static::deleting(function ($model) {
            if ($model instanceof Product) {
                $model->deleteImages($model->image()->get());
            }
            else {
                $model->deleteImage($model->image()->get());
            }

        });

    }

    public function image(){
        return $this->morphOne(Image::class,'imageable');

    }

    public function storeImage($url) {
        $image = $this->image()->create(['url' => $url]);
    }

    public function updateImage($url) {
        $this->image[0]?->delete();
        $this->storeImage($url);
    }

    public function deleteImages($images) {
        foreach ($images as $image) {
            $image[0]?->delete();
        }
    }

    public function deleteImage($image) {
        //dd($image[0]);
        $image[0]?->delete();
    }

    public function getImageAttribute() {
        return $this->image()
            ->get(['imageable_type', 'url'])
            ->map(function($image) {
                unset($image->imageable_type);
                return asset('public/storage/' . $image->url);
            });
    }

}
