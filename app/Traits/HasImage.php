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
                $model->deleteImages();
            }
            else {
                $model->deleteImage();
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
        $this->image?->delete();
        $this->storeImage($url);
    }

    public function deleteImages() {
        $images = $this->image;
        foreach ($images as $image) {
            $image?->delete();
        }
    }

    public function deleteImage() {
        $this->image?->delete();
    }

    public function getImageAttribute() {
        return $this->image()
            ->get(['imageable_type', 'url'])
            ->map(function($image) {
                unset($image->imageable_type);
                return asset('public/' . $image->url);
            });
    }

}
