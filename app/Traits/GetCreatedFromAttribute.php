<?php

namespace App\Traits;

trait GetCreatedFromAttribute
{
    function getCreatedFromAttribute($model) {
        $model['created_from'] = $model->created_at->diffForHumans();
    }
}
