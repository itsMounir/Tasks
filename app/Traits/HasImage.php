<?php

namespace App\Traits;

use App\Models\Image;

trait HasImage
{

    public static function bootHasImage()
    {
        static::deleting(function ($model) {
            $model->deleteImages();
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

}
